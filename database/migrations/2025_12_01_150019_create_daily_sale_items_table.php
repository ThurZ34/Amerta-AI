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
        Schema::create('daily_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_sale_id')->constrained('daily_sales')->onDelete('cascade');
            $table->unsignedBigInteger('produk_id');
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 15, 2); // Snapshot of price
            $table->decimal('cost', 15, 2); // Snapshot of cost
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sale_items');
    }
};
