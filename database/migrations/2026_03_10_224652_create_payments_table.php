<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('payment_number')->unique();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash_on_delivery']);
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('payment_details')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('payment_number');
            $table->index('payment_status');
            $table->index('transaction_id');
            $table->index(['order_id', 'payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
