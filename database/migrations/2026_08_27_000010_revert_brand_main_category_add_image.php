<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('brands', 'main_product_category_id')) {
            try {
                Schema::table('brands', function (Blueprint $table) {
                    $table->dropForeign(['main_product_category_id']);
                });
            } catch (\Throwable) {
            }

            try {
                Schema::table('brands', function (Blueprint $table) {
                    $table->dropUnique(['main_product_category_id', 'name']);
                });
            } catch (\Throwable) {
            }

            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('main_product_category_id');
            });
        }

        try {
            Schema::table('brands', function (Blueprint $table) {
                $table->unique('name');
            });
        } catch (\Throwable) {
        }

        if (! Schema::hasColumn('brands', 'image_url')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->string('image_url')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('brands', 'image_url')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }

        try {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropUnique(['name']);
            });
        } catch (\Throwable) {
        }

        if (! Schema::hasColumn('brands', 'main_product_category_id')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->foreignId('main_product_category_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->restrictOnDelete();

                $table->unique(['main_product_category_id', 'name']);
            });
        }
    }
};
