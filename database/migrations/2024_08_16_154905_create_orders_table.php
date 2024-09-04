<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product-id');
            $table->foreign('product-id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('main-order-id');
            $table->foreign('main-order-id')->references('id')->on('main_orders')->onDelete('cascade');
            $table->integer('count');
            $table->string('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
