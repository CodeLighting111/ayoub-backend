<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreignId('complaint_id')->nullable()->after('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->after('complaint_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropForeign(['complaint_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['complaint_id', 'product_id']);
            $table->dropForeign(['order_id']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable(false)->change();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }
};
