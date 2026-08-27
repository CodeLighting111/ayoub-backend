<?php

namespace Tests\Feature;

use App\Models\MainProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainProductCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_main_product_categories(): void
    {
        MainProductCategory::query()->create(['title' => 'البقالة', 'image_url' => '/images/main-product-categories/test-grocery.png']);
        MainProductCategory::query()->create(['title' => 'المنظفات', 'image_url' => '/images/main-product-categories/test-cleaning.png']);

        $this->getJson('/api/main-product-categories')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', 'البقالة')
            ->assertJsonPath('data.1.title', 'المنظفات');
    }

    public function test_it_shows_a_main_product_category(): void
    {
        $category = MainProductCategory::query()->create([
            'title' => 'المشروبات',
            'image_url' => '/images/main-product-categories/test-drinks.png',
        ]);

        $this->getJson('/api/main-product-categories/'.$category->id)
            ->assertOk()
            ->assertJsonPath('data.title', 'المشروبات')
            ->assertJsonPath('data.image_url', '/images/main-product-categories/test-drinks.png')
            ->assertJsonPath('data.id', $category->id);
    }

    public function test_it_returns_not_found_for_missing_category(): void
    {
        $this->getJson('/api/main-product-categories/999')->assertNotFound();
    }
}
