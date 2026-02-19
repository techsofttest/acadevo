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
        Schema::create('product_reviews', function (Blueprint $table) {
    $table->id();

    $table->foreignId('product_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('customer_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('order_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->unsignedTinyInteger('rating'); // 1–5
    $table->text('review');

    $table->boolean('is_approved')->default(false);
    $table->timestamps();

    // 🔒 one review per customer per product
    $table->unique(['product_id', 'customer_id']);
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
