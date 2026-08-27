<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('pending');
            $table->string('payment_method');
            $table->string('payment_status')->default('unpaid');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('branch_name')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('governorate_name')->nullable();
            $table->string('city_name')->nullable();
            $table->string('area_name')->nullable();
            $table->timestamp('expected_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
