<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('movement_type', ['purchase', 'sale', 'return', 'adjustment', 'transfer', 'damage', 'theft']);
            $table->integer('quantity_change');
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->text('reason')->nullable();
            $table->string('reference_number')->nullable();
            $table->foreignId('related_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->timestamps();

            $table->index('product_id');
            $table->index('movement_type');
            $table->index('created_at');
            $table->index(['product_id', 'movement_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
