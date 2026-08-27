<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AreaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_areas(): void
    {
        $cairo = Governorate::query()->create(['name' => 'القاهرة']);
        $nasrCity = City::query()->create(['governorate_id' => $cairo->id, 'name' => 'مدينة نصر']);
        $maadiCity = City::query()->create(['governorate_id' => $cairo->id, 'name' => 'المعادي']);

        Area::query()->create(['city_id' => $nasrCity->id, 'name' => 'الحي السابع']);
        Area::query()->create(['city_id' => $maadiCity->id, 'name' => 'زهراء المعادي']);

        $this->getJson('/api/areas')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'الحي السابع')
            ->assertJsonPath('data.1.name', 'زهراء المعادي');
    }

    public function test_it_filters_areas_by_city(): void
    {
        $cairo = Governorate::query()->create(['name' => 'القاهرة']);
        $nasrCity = City::query()->create(['governorate_id' => $cairo->id, 'name' => 'مدينة نصر']);
        $maadiCity = City::query()->create(['governorate_id' => $cairo->id, 'name' => 'المعادي']);

        Area::query()->create(['city_id' => $nasrCity->id, 'name' => 'الحي السابع']);
        Area::query()->create(['city_id' => $maadiCity->id, 'name' => 'زهراء المعادي']);

        $this->getJson('/api/areas?city_id='.$nasrCity->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'الحي السابع')
            ->assertJsonPath('data.0.city_id', $nasrCity->id);
    }

    public function test_it_shows_an_area(): void
    {
        $cairo = Governorate::query()->create(['name' => 'القاهرة']);
        $city = City::query()->create(['governorate_id' => $cairo->id, 'name' => 'مدينة نصر']);
        $area = Area::query()->create(['city_id' => $city->id, 'name' => 'الحي السابع']);

        $this->getJson('/api/areas/'.$area->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'الحي السابع')
            ->assertJsonPath('data.city.name', 'مدينة نصر')
            ->assertJsonPath('data.city.governorate.name', 'القاهرة');
    }

    public function test_it_returns_not_found_for_missing_area(): void
    {
        $this->getJson('/api/areas/999')->assertNotFound();
    }
}
