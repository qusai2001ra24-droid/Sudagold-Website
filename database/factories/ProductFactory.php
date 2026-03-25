<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $goldPurities = ['24k', '22k', '18k', '14k'];
        $weight = fake()->randomFloat(3, 1, 50);
        $goldPricePerGram = fake()->randomFloat(2, 50, 100);
        $makingCost = fake()->randomFloat(2, 50, 500);
        $price = ($weight * $goldPricePerGram) + $makingCost;

        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'sku' => strtoupper(Str::random(3)) . '-' . fake()->unique()->numerify('####'),
            'description' => fake()->paragraph(),
            'gold_purity' => fake()->randomElement($goldPurities),
            'weight' => $weight,
            'making_cost' => $makingCost,
            'gold_price_per_gram' => $goldPricePerGram,
            'price' => round($price, 2),
            'special_price' => null,
            'stock_quantity' => fake()->numberBetween(0, 100),
            'low_stock_threshold' => 5,
            'image' => null,
            'images' => [],
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 3,
        ]);
    }

    public function withSpecialPrice(): static
    {
        return $this->state(fn (array $attributes) => [
            'special_price' => round($attributes['price'] * 0.85, 2),
        ]);
    }
}
