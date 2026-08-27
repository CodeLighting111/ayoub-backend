<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\City;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\Governorate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    private function createClientFixture(string $status = 'active'): Client
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
            'status' => $status,
        ]);
    }

    public function test_it_lists_clients(): void
    {
        $first = $this->createClientFixture();

        $secondCategory = ClientCategory::query()->create(['title' => 'جملة']);
        Client::query()->create([
            'client_category_id' => $secondCategory->id,
            'name' => 'مؤسسة الأمل',
            'phone' => '01009876543',
            'password' => 'secret123',
            'branch_name' => 'فرع المعادي',
            'governorate_id' => $first->governorate_id,
            'city_id' => $first->city_id,
            'area_id' => $first->area_id,
            'status' => 'suspended',
        ]);

        $this->getJson('/api/clients')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonMissing(['password']);
    }

    public function test_it_filters_clients_by_city(): void
    {
        $client = $this->createClientFixture();

        $this->getJson('/api/clients?city_id='.$client->city_id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'سوبر ماركت الحسن');
    }

    public function test_it_shows_a_client(): void
    {
        $client = $this->createClientFixture();

        $this->getJson('/api/clients/'.$client->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'سوبر ماركت الحسن')
            ->assertJsonPath('data.category.title', 'سوبر ماركت')
            ->assertJsonPath('data.area.name', 'الحي السابع')
            ->assertJsonMissing(['password']);
    }

    public function test_it_returns_not_found_for_missing_client(): void
    {
        $this->getJson('/api/clients/999')->assertNotFound();
    }
}
