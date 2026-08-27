<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Complaint;
use App\Models\Governorate;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintApiTest extends TestCase
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

    public function test_it_creates_a_complaint(): void
    {
        $client = $this->createClientFixture();

        $this->postJson('/api/complaints', [
            'client_id' => $client->id,
            'subject' => 'تأخر في التوصيل',
            'message' => 'تأخر الطلب أكثر من ساعتين.',
        ])
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.subject', 'تأخر في التوصيل')
            ->assertJsonPath('data.client_name', 'سوبر ماركت الحسن');

        $this->assertDatabaseCount('complaints', 1);
    }

    public function test_it_lists_complaints_for_a_client(): void
    {
        $client = $this->createClientFixture();

        Complaint::query()->create([
            'client_id' => $client->id,
            'status' => 'pending',
            'subject' => 'منتج تالف',
            'message' => 'وصل المنتج تالفاً.',
            'client_name' => $client->name,
            'client_phone' => $client->phone,
        ]);

        $this->getJson('/api/complaints?client_id='.$client->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.status_label', 'قيد المراجعة');
    }

    public function test_it_shows_a_complaint(): void
    {
        $client = $this->createClientFixture();

        $complaint = Complaint::query()->create([
            'client_id' => $client->id,
            'status' => 'pending',
            'subject' => 'مشكلة في الفاتورة',
            'message' => 'الفاتورة غير صحيحة.',
            'client_name' => $client->name,
            'client_phone' => $client->phone,
        ]);

        $this->getJson('/api/complaints/'.$complaint->id)
            ->assertOk()
            ->assertJsonPath('data.id', $complaint->id)
            ->assertJsonPath('data.message', 'الفاتورة غير صحيحة.');
    }

    public function test_it_rejects_complaint_with_invalid_order(): void
    {
        $client = $this->createClientFixture();

        $otherCategory = ClientCategory::query()->create(['title' => 'جملة']);
        $otherClient = Client::query()->create([
            'client_category_id' => $otherCategory->id,
            'name' => 'مؤسسة الأمل',
            'phone' => '01009876543',
            'password' => 'secret123',
            'branch_name' => 'فرع المعادي',
            'governorate_id' => $client->governorate_id,
            'city_id' => $client->city_id,
            'area_id' => $client->area_id,
            'address' => 'شارع آخر',
            'status' => 'active',
        ]);

        $order = Order::query()->create([
            'order_number' => 'ORD-2026-9999',
            'client_id' => $otherClient->id,
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'unpaid',
            'subtotal' => 100,
            'delivery_fee' => 30,
            'total' => 130,
            'client_name' => $otherClient->name,
            'client_phone' => $otherClient->phone,
        ]);

        $this->postJson('/api/complaints', [
            'client_id' => $client->id,
            'subject' => 'مشكلة',
            'message' => 'تفاصيل',
            'order_id' => $order->id,
        ])->assertUnprocessable();
    }
}
