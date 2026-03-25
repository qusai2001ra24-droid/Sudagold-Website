<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->enum('gold_purity', ['24k', '22k', '18k', '14k', '10k']);
            $table->decimal('weight', 10, 3)->comment('Weight in grams');
            $table->decimal('making_cost', 12, 2)->comment('Labor and making charges');
            $table->decimal('gold_price_per_gram', 10, 2)->comment('Gold price per gram at time of creation');
            $table->decimal('price', 12, 2);
            $table->decimal('special_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('sku');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('price');
            $table->index(['category_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
