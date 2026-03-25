<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);
        $taxAmount = round($subtotal * 0.08, 2);
        $shippingAmount = 0;
        $discountAmount = 0;
        $totalPrice = $subtotal + $taxAmount + $shippingAmount - $discountAmount;

        return [
            'user_id' => User::factory(),
            'order_number' => Order::generateOrderNumber(),
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'payment_method' => fake()->randomElement(['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash_on_delivery']),
            'order_status' => 'pending',
            'shipping_first_name' => fake()->firstName(),
            'shipping_last_name' => fake()->lastName(),
            'shipping_email' => fake()->safeEmail(),
            'shipping_phone' => fake()->phoneNumber(),
            'shipping_address' => fake()->streetAddress(),
            'shipping_city' => fake()->city(),
            'shipping_state' => fake()->state(),
            'shipping_zip_code' => fake()->postcode(),
            'shipping_country' => fake()->country(),
            'billing_first_name' => fake()->firstName(),
            'billing_last_name' => fake()->lastName(),
            'billing_address' => fake()->streetAddress(),
            'billing_city' => fake()->city(),
            'billing_state' => fake()->state(),
            'billing_zip_code' => fake()->postcode(),
            'billing_country' => fake()->country(),
            'ordered_at' => now(),
        ];
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'processing',
        ]);
    }

    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'delivered',
            'shipped_at' => now()->subDays(3),
            'delivered_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'order_status' => 'cancelled',
        ]);
    }
}
