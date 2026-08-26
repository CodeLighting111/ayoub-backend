<?php

namespace Tests\Feature;

use App\Models\ClientCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_client_categories(): void
    {
        ClientCategory::query()->create(['title' => 'المطاعم']);
        ClientCategory::query()->create(['title' => 'سوبر ماركت']);

        $response = $this->getJson('/api/client-categories');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', 'المطاعم')
            ->assertJsonPath('data.1.title', 'سوبر ماركت');
    }

    public function test_it_shows_a_client_category(): void
    {
        $category = ClientCategory::query()->create(['title' => 'تجار التجزئة']);

        $this->getJson('/api/client-categories/'.$category->id)
            ->assertOk()
            ->assertJsonPath('data.title', 'تجار التجزئة')
            ->assertJsonPath('data.id', $category->id);
    }

    public function test_it_returns_not_found_for_missing_category(): void
    {
        $this->getJson('/api/client-categories/999')->assertNotFound();
    }
}
