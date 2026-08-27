<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_product_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->timestamps();

            $table->unique(['main_product_category_id', 'title']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_product_categories');
    }
};
