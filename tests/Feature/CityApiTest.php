<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Governorate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_cities(): void
    {
        $cairo = Governorate::query()->create(['name' => 'القاهرة']);
        $giza = Governorate::query()->create(['name' => 'الجيزة']);

        City::query()->create(['governorate_id' => $cairo->id, 'name' => 'مدينة نصر']);
        City::query()->create(['governorate_id' => $giza->id, 'name' => '6 أكتوبر']);

        $this->getJson('/api/cities')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', '6 أكتوبر')
            ->assertJsonPath('data.1.name', 'مدينة نصر');
    }

    public function test_it_filters_cities_by_governorate(): void
    {
        $cairo = Governorate::query()->create(['name' => 'القاهرة']);
        $giza = Governorate::query()->create(['name' => 'الجيزة']);

        City::query()->create(['governorate_id' => $cairo->id, 'name' => 'مدينة نصر']);
        City::query()->create(['governorate_id' => $giza->id, 'name' => '6 أكتوبر']);

        $this->getJson('/api/cities?governorate_id='.$cairo->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'مدينة نصر')
            ->assertJsonPath('data.0.governorate_id', $cairo->id);
    }

    public function test_it_shows_a_city(): void
    {
        $governorate = Governorate::query()->create(['name' => 'الإسكندرية']);
        $city = City::query()->create(['governorate_id' => $governorate->id, 'name' => 'سموحة']);

        $this->getJson('/api/cities/'.$city->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'سموحة')
            ->assertJsonPath('data.governorate.name', 'الإسكندرية');
    }

    public function test_it_returns_not_found_for_missing_city(): void
    {
        $this->getJson('/api/cities/999')->assertNotFound();
    }
}
