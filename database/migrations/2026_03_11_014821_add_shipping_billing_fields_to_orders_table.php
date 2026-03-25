<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_first_name', 255)->nullable()->after('order_status');
            $table->string('shipping_last_name', 255)->nullable()->after('shipping_first_name');
            $table->string('shipping_email', 255)->nullable()->after('shipping_last_name');
            $table->string('shipping_phone', 50)->nullable()->after('shipping_email');
            $table->string('billing_first_name', 255)->nullable()->after('shipping_country');
            $table->string('billing_last_name', 255)->nullable()->after('billing_first_name');
            $table->string('billing_address', 500)->nullable()->change();
            $table->string('billing_city', 255)->nullable()->after('billing_address');
            $table->string('billing_state', 255)->nullable()->after('billing_city');
            $table->string('billing_zip_code', 20)->nullable()->after('billing_state');
            $table->string('billing_country', 255)->nullable()->after('billing_zip_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_first_name',
                'shipping_last_name',
                'shipping_email',
                'shipping_phone',
                'billing_first_name',
                'billing_last_name',
                'billing_city',
                'billing_state',
                'billing_zip_code',
                'billing_country',
            ]);
        });
    }
};
