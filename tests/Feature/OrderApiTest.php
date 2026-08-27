<?php

namespace Tests\Feature;

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
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private function createClientFixture(): Client
    {
        $category = ClientCategory::query()->create(['title' => 'سوبر ماركت']);
        $governorate = Governorate::query()->create(['name' => 'القاهرة']);
        $city = City::query()->create(['governorate_id' => $governorate->id, 'name' => 'مدينة نصر']);
        $area = Area::query()->create(['city_id' => $city->id, 'name' => 'الحي السابع']);

        return Client::query()->create([
            'client_category_id' => $category->id,
            'name' => 'سوبر ماركت الحسن',
            'phone' => '01001234567',
            'password' => 'secret123',
            'branch_name' => 'فرع مدينة نصر',
            'responsible_person' => 'محمد عبد الله',
            'governorate_id' => $governorate->id,
            'city_id' => $city->id,
            'area_id' => $area->id,
            'address' => 'شارع عباس العقاد',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'status' => 'active',
        ]);
    }

    private function createProductFixture(int $stock = 10, float $price = 99, ?float $discountPrice = null): Product
    {
        $mainCategory = MainProductCategory::query()->create(['title' => 'المشروبات']);
        $subCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'مياه صغيرة',
        ]);
        $brand = Brand::query()->create(['name' => 'Nestle', 'image_url' => '/images/brands/test.png']);

        return Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $subCategory->id,
            'name' => 'مياه Nestle 600ml',
            'image_url' => '/images/products/test-water.png',
            'price' => $price,
            'discount_price' => $discountPrice,
            'pieces' => 12,
            'stock' => $stock,
            'status' => 'active',
            'unit_label' => 'كرتونة (12 زجاجة)',
        ]);
    }

    public function test_it_creates_an_order(): void
    {
        $client = $this->createClientFixture();
        $product = $this->createProductFixture(stock: 5, price: 120, discountPrice: 99);

        $this->postJson('/api/orders', [
            'client_id' => $client->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.payment_method', 'cash')
            ->assertJsonPath('data.subtotal', '198.00')
            ->assertJsonPath('data.delivery_fee', number_format(OrderService::DELIVERY_FEE, 2, '.', ''))
            ->assertJsonPath('data.total', number_format(198 + OrderService::DELIVERY_FEE, 2, '.', ''))
            ->assertJsonPath('data.client_name', 'سوبر ماركت الحسن')
            ->assertJsonCount(1, 'data.items');

        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_it_lists_orders_for_a_client(): void
    {
        $client = $this->createClientFixture();
        $product = $this->createProductFixture();

        $this->postJson('/api/orders', [
            'client_id' => $client->id,
            'payment_method' => 'wallet',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ])->assertCreated();

        $this->getJson('/api/orders?client_id='.$client->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.payment_method', 'wallet')
            ->assertJsonPath('data.0.items_count', 1);
    }

    public function test_it_shows_an_order(): void
    {
        $client = $this->createClientFixture();
        $product = $this->createProductFixture();

        $createResponse = $this->postJson('/api/orders', [
            'client_id' => $client->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 1],
            ],
        ])->assertCreated();

        $orderId = $createResponse->json('data.id');

        $this->getJson('/api/orders/'.$orderId)
            ->assertOk()
            ->assertJsonPath('data.id', $orderId)
            ->assertJsonPath('data.items.0.product_name', 'مياه Nestle 600ml');
    }

    public function test_it_cancels_a_pending_order_and_restores_stock(): void
    {
        $client = $this->createClientFixture();
        $product = $this->createProductFixture(stock: 8);

        $createResponse = $this->postJson('/api/orders', [
            'client_id' => $client->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 3],
            ],
        ])->assertCreated();

        $orderId = $createResponse->json('data.id');

        $this->assertSame(5, $product->fresh()->stock);

        $this->postJson('/api/orders/'.$orderId.'/cancel')
            ->assertOk()
            ->assertJsonPath('data.status', 'cancelled');

        $this->assertSame(8, $product->fresh()->stock);
    }

    public function test_it_rejects_order_when_stock_is_insufficient(): void
    {
        $client = $this->createClientFixture();
        $product = $this->createProductFixture(stock: 1);

        $this->postJson('/api/orders', [
            'client_id' => $client->id,
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $product->id, 'quantity' => 5],
            ],
        ])->assertUnprocessable();
    }

    public function test_it_returns_not_found_for_missing_order(): void
    {
        $this->getJson('/api/orders/999')->assertNotFound();
    }
}
