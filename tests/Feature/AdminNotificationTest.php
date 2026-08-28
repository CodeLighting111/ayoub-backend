<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Area;
use App\Models\Brand;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Governorate;
use App\Models\MainProductCategory;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubProductCategory;
use App\Services\AdminNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_notifications_page(): void
    {
        $this->get(route('admin.notifications.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_notifications_page(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrder();

        AdminNotification::query()->create([
            'order_id' => $order->id,
            'type' => AdminNotification::TYPE_NEW_ORDER,
            'title' => 'طلب جديد #'.$order->order_number,
            'message' => 'تجريبي',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.notifications.index'))
            ->assertOk()
            ->assertSee('الإشعارات')
            ->assertSee($order->order_number);
    }

    public function test_clicking_notification_marks_it_read_and_redirects_to_order(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrder();

        $notification = AdminNotification::query()->create([
            'order_id' => $order->id,
            'type' => AdminNotification::TYPE_NEW_ORDER,
            'title' => 'طلب جديد #'.$order->order_number,
            'message' => 'تجريبي',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.notifications.show', $notification))
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_admin_can_mark_all_notifications_as_read(): void
    {
        $admin = $this->createAdmin();
        $order = $this->createOrder();

        AdminNotification::query()->create([
            'order_id' => $order->id,
            'type' => AdminNotification::TYPE_NEW_ORDER,
            'title' => 'طلب جديد',
            'message' => 'تجريبي',
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.notifications.mark-all-read'))
            ->assertRedirect(route('admin.notifications.index'));

        $this->assertSame(0, app(AdminNotificationService::class)->unreadCount());
    }

    public function test_new_order_creates_notification(): void
    {
        $client = $this->createClientWithProduct();

        $this->postJson('/api/orders', [
            'client_id' => $client['client']->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $client['product']->id, 'quantity' => 1],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('admin_notifications', [
            'type' => AdminNotification::TYPE_NEW_ORDER,
        ]);
    }

    public function test_client_cancel_creates_notification(): void
    {
        $client = $this->createClientWithProduct();

        $orderResponse = $this->postJson('/api/orders', [
            'client_id' => $client['client']->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $client['product']->id, 'quantity' => 1],
            ],
        ])->assertCreated();

        $orderId = $orderResponse->json('data.id');

        $this->postJson("/api/orders/{$orderId}/cancel")->assertOk();

        $this->assertDatabaseHas('admin_notifications', [
            'order_id' => $orderId,
            'type' => AdminNotification::TYPE_ORDER_CANCELLED,
        ]);
    }

    public function test_new_complaint_creates_notification(): void
    {
        $client = $this->createClientWithProduct();

        $this->postJson('/api/complaints', [
            'client_id' => $client['client']->id,
            'subject' => 'تأخر التوصيل',
            'message' => 'الطلب تأخر عن الموعد المحدد.',
        ])->assertCreated();

        $this->assertDatabaseHas('admin_notifications', [
            'type' => AdminNotification::TYPE_NEW_COMPLAINT,
        ]);
    }

    public function test_low_stock_notification_is_created_when_stock_reaches_threshold(): void
    {
        $client = $this->createClientWithProduct(stock: 11);

        $this->postJson('/api/orders', [
            'client_id' => $client['client']->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $client['product']->id, 'quantity' => 1],
            ],
        ])->assertCreated();

        $this->assertDatabaseHas('admin_notifications', [
            'product_id' => $client['product']->id,
            'type' => AdminNotification::TYPE_LOW_STOCK,
        ]);
    }

    public function test_low_stock_notification_is_not_repeated_while_already_low(): void
    {
        $client = $this->createClientWithProduct(stock: 11);

        $this->postJson('/api/orders', [
            'client_id' => $client['client']->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $client['product']->id, 'quantity' => 2],
            ],
        ])->assertCreated();

        $this->assertSame(
            1,
            AdminNotification::query()->where('type', AdminNotification::TYPE_LOW_STOCK)->count(),
        );
    }

    private function createAdmin(): Admin
    {
        return Admin::query()->create([
            'name' => 'Ayoub',
            'email' => 'ayoub@gmail.com',
            'password' => 'admin123',
            'role' => 'superadmin',
        ]);
    }

    private function createOrder(): Order
    {
        $fixture = $this->createClientWithProduct();

        return Order::query()->create([
            'order_number' => 'ORD-TEST-001',
            'client_id' => $fixture['client']->id,
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'subtotal' => 100,
            'delivery_fee' => 30,
            'total' => 130,
            'client_name' => $fixture['client']->name,
            'client_phone' => $fixture['client']->phone,
            'branch_name' => $fixture['client']->branch_name,
            'delivery_address' => 'عنوان تجريبي',
        ]);
    }

    /** @return array{client: Client, product: Product} */
    private function createClientWithProduct(int $stock = 10): array
    {
        $category = ClientCategory::query()->create(['title' => 'سوبر ماركت']);
        $governorate = Governorate::query()->create(['name' => 'القاهرة']);
        $city = City::query()->create(['governorate_id' => $governorate->id, 'name' => 'مدينة نصر']);
        $area = Area::query()->create(['city_id' => $city->id, 'name' => 'الحي السابع']);

        $client = Client::query()->create([
            'client_category_id' => $category->id,
            'name' => 'متجر النور',
            'phone' => '01012345678',
            'password' => 'secret',
            'branch_name' => 'فرع رئيسي',
            'governorate_id' => $governorate->id,
            'city_id' => $city->id,
            'area_id' => $area->id,
            'address' => 'عنوان تجريبي',
            'status' => 'active',
        ]);

        $mainCategory = MainProductCategory::query()->create(['title' => 'المشروبات']);
        $subCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'مياه',
        ]);
        $brand = Brand::query()->create(['name' => 'Nestle', 'image_url' => '/images/brands/test.png']);

        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $subCategory->id,
            'name' => 'منتج تجريبي',
            'price' => 50,
            'stock' => $stock,
            'status' => 'active',
            'unit_label' => 'قطعة',
        ]);

        return ['client' => $client, 'product' => $product];
    }
}
