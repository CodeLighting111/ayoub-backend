<?php

namespace Tests\Feature;

use App\Models\MainProductCategory;
use App\Models\SubProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubProductCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_sub_product_categories(): void
    {
        $grocery = MainProductCategory::query()->create(['title' => 'البقالة']);
        $cleaning = MainProductCategory::query()->create(['title' => 'المنظفات']);

        SubProductCategory::query()->create(['main_product_category_id' => $grocery->id, 'title' => 'أرز']);
        SubProductCategory::query()->create(['main_product_category_id' => $cleaning->id, 'title' => 'صابون']);

        $this->getJson('/api/sub-product-categories')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', 'أرز')
            ->assertJsonPath('data.1.title', 'صابون');
    }

    public function test_it_filters_sub_product_categories_by_main_category(): void
    {
        $grocery = MainProductCategory::query()->create(['title' => 'البقالة']);
        $cleaning = MainProductCategory::query()->create(['title' => 'المنظفات']);

        SubProductCategory::query()->create(['main_product_category_id' => $grocery->id, 'title' => 'أرز']);
        SubProductCategory::query()->create(['main_product_category_id' => $cleaning->id, 'title' => 'صابون']);

        $this->getJson('/api/sub-product-categories?main_product_category_id='.$grocery->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'أرز')
            ->assertJsonPath('data.0.main_product_category_id', $grocery->id);
    }

    public function test_it_shows_a_sub_product_category(): void
    {
        $mainCategory = MainProductCategory::query()->create(['title' => 'المشروبات']);
        $subCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'عصائر',
        ]);

        $this->getJson('/api/sub-product-categories/'.$subCategory->id)
            ->assertOk()
            ->assertJsonPath('data.title', 'عصائر')
            ->assertJsonPath('data.main_category.title', 'المشروبات');
    }

    public function test_it_returns_not_found_for_missing_sub_product_category(): void
    {
        $this->getJson('/api/sub-product-categories/999')->assertNotFound();
    }
}
