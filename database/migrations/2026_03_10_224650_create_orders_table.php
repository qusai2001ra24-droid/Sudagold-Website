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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->enum('payment_method', ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash_on_delivery']);
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])->default('pending');
            $table->text('shipping_address');
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_zip_code', 20)->nullable();
            $table->string('shipping_country', 100)->nullable();
            $table->text('billing_address')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index('order_number');
            $table->index('order_status');
            $table->index('ordered_at');
            $table->index(['user_id', 'order_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
