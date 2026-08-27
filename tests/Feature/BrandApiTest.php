<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_brands(): void
    {
        Brand::query()->create(['name' => 'بيبسي', 'image_url' => '/images/brands/test-pepsi.png']);
        Brand::query()->create(['name' => 'المراعي', 'image_url' => '/images/brands/test-almarai.png']);

        $response = $this->getJson('/api/brands');

        $response->assertOk()
            ->assertJsonCount(2, 'data');

        $names = collect($response->json('data'))->pluck('name')->all();
        $this->assertEqualsCanonicalizing(['بيبسي', 'المراعي'], $names);
    }

    public function test_it_shows_a_brand(): void
    {
        $brand = Brand::query()->create(['name' => 'المراعي', 'image_url' => '/images/brands/test-almarai.png']);

        $this->getJson('/api/brands/'.$brand->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'المراعي')
            ->assertJsonPath('data.image_url', '/images/brands/test-almarai.png');
    }

    public function test_it_returns_not_found_for_missing_brand(): void
    {
        $this->getJson('/api/brands/999')->assertNotFound();
    }
}
