<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->foreignId('main_product_category_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->restrictOnDelete();

            $table->dropUnique(['name']);
            $table->unique(['main_product_category_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropUnique(['main_product_category_id', 'name']);
            $table->unique('name');

            $table->dropConstrainedForeignId('main_product_category_id');
        });
    }
};
