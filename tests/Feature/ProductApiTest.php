<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\MainProductCategory;
use App\Models\Product;
use App\Models\SubProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_products(): void
    {
        $mainCategory = MainProductCategory::query()->create([
            'title' => 'المشروبات',
            'image_url' => '/images/main-product-categories/test-drinks.png',
        ]);
        $subCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'مياه صغيرة',
        ]);
        $brand = Brand::query()->create([
            'name' => 'Nestle',
            'image_url' => '/images/brands/test-nestle.png',
        ]);

        Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $subCategory->id,
            'name' => 'مياه Nestle 600ml',
            'image_url' => '/images/products/test-water.png',
            'price' => 120,
            'discount_price' => 99,
            'pieces' => 12,
            'stock' => 50,
            'status' => 'active',
            'unit_label' => 'كرتونة (12 زجاجة)',
        ]);

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'مياه Nestle 600ml')
            ->assertJsonPath('data.0.brand.name', 'Nestle')
            ->assertJsonPath('data.0.sub_category.title', 'مياه صغيرة')
            ->assertJsonPath('data.0.main_category.title', 'المشروبات')
            ->assertJsonPath('data.0.is_available', true);
    }

    public function test_it_filters_products_by_sub_category(): void
    {
        $mainCategory = MainProductCategory::query()->create(['title' => 'البقالة']);
        $waterCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'مياه',
        ]);
        $riceCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'أرز',
        ]);
        $brand = Brand::query()->create(['name' => 'Local', 'image_url' => '/images/brands/test.png']);

        Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $waterCategory->id,
            'name' => 'مياه',
            'image_url' => '/images/products/water.png',
            'price' => 10,
            'pieces' => 1,
            'stock' => 5,
            'status' => 'active',
        ]);
        Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $riceCategory->id,
            'name' => 'أرز',
            'image_url' => '/images/products/rice.png',
            'price' => 20,
            'pieces' => 1,
            'stock' => 5,
            'status' => 'active',
        ]);

        $this->getJson('/api/products?sub_product_category_id='.$waterCategory->id)
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'مياه');
    }

    public function test_it_shows_a_product(): void
    {
        $mainCategory = MainProductCategory::query()->create(['title' => 'البقالة']);
        $subCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'مياه صغيرة',
        ]);
        $brand = Brand::query()->create(['name' => 'Nestle', 'image_url' => '/images/brands/test.png']);
        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $subCategory->id,
            'name' => 'مياه Nestle',
            'image_url' => '/images/products/test.png',
            'price' => 99,
            'pieces' => 1,
            'stock' => 10,
            'status' => 'active',
        ]);

        $this->getJson('/api/products/'.$product->id)
            ->assertOk()
            ->assertJsonPath('data.name', 'مياه Nestle')
            ->assertJsonPath('data.brand.name', 'Nestle');
    }

    public function test_it_sets_status_inactive_when_stock_is_zero(): void
    {
        $mainCategory = MainProductCategory::query()->create(['title' => 'البقالة']);
        $subCategory = SubProductCategory::query()->create([
            'main_product_category_id' => $mainCategory->id,
            'title' => 'أرز',
        ]);
        $brand = Brand::query()->create(['name' => 'الضحى', 'image_url' => '/images/brands/test.png']);

        $product = Product::query()->create([
            'brand_id' => $brand->id,
            'sub_product_category_id' => $subCategory->id,
            'name' => 'أرز الضحى 1ك',
            'image_url' => '/images/products/rice.png',
            'price' => 50,
            'pieces' => 1,
            'stock' => 0,
            'status' => 'active',
        ]);

        $this->assertSame('inactive', $product->fresh()->status);

        $product->update(['stock' => 100, 'status' => 'active']);
        $product->update(['stock' => 0]);

        $this->assertSame('inactive', $product->fresh()->status);
    }

    public function test_it_returns_not_found_for_missing_product(): void
    {
        $this->getJson('/api/products/999')->assertNotFound();
    }
}
