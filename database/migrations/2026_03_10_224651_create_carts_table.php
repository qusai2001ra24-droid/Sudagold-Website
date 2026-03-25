<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->integer('item_count')->default(0);
            $table->timestamps();

            $table->unique('user_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
