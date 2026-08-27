<?php

namespace Tests\Feature;

use App\Models\Governorate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernorateApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_governorates(): void
    {
        Governorate::query()->create(['name' => 'القاهرة']);
        Governorate::query()->create(['name' => 'الجيزة']);

        $response = $this->getJson('/api/governorates');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.name', 'الجيزة')
            ->assertJsonPath('data.1.name', 'القاهرة');
    }

    public function test_it_shows_a_governorate(): void
    {
        $governorate = Governorate::query()->create(['name' => 'الإسكندرية']);

        $this->getJson('/api/governorates/'.$governorate->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'الإسكندرية')
            ->assertJsonPath('data.id', $governorate->id);
    }

    public function test_it_returns_not_found_for_missing_governorate(): void
    {
        $this->getJson('/api/governorates/999')->assertNotFound();
    }
}
