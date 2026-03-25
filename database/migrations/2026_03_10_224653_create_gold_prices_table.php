<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gold_prices', function (Blueprint $table) {
            $table->id();
            $table->enum('purity', ['24k', '22k', '18k', '14k', '10k']);
            $table->decimal('price_per_gram', 10, 2);
            $table->decimal('price_per_ounce', 12, 2)->nullable();
            $table->decimal('price_per_tola', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('source')->nullable()->comment('API source or manual entry');
            $table->boolean('is_active')->default(true);
            $table->timestamp('effective_from');
            $table->timestamp('effective_until')->nullable();
            $table->timestamps();

            $table->index('purity');
            $table->index('effective_from');
            $table->index(['purity', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gold_prices');
    }
};
