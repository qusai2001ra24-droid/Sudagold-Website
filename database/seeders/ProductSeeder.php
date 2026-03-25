<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GoldPrice;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'خواتم', 'slug' => 'rings', 'description' => 'خواتم ذهبية فاخرة', 'sort_order' => 1],
            ['name' => 'قلائد', 'slug' => 'necklaces', 'description' => 'قلائد ذهبية أنيقة', 'sort_order' => 2],
            ['name' => 'أساور', 'slug' => 'bracelets', 'description' => 'أساور ذهبية راقية', 'sort_order' => 3],
            ['name' => 'حلقات الأذن', 'slug' => 'earrings', 'description' => 'حلقات أذن ذهبية', 'sort_order' => 4],
            ['name' => 'ساعات', 'slug' => 'watches', 'description' => 'ساعات ذهبية فاخرة', 'sort_order' => 5],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            $this->createProductsForCategory($category);
        }

        $goldPrices = [
            ['purity' => '24k', 'price_per_gram' => 92.50, 'price_per_ounce' => 2876.00, 'price_per_tola' => 1078.50, 'source' => 'London Fix', 'is_active' => true],
            ['purity' => '22k', 'price_per_gram' => 84.79, 'price_per_ounce' => 2636.33, 'price_per_tola' => 988.50, 'source' => 'London Fix', 'is_active' => true],
            ['purity' => '18k', 'price_per_gram' => 69.38, 'price_per_ounce' => 2157.00, 'price_per_tola' => 808.50, 'source' => 'London Fix', 'is_active' => true],
            ['purity' => '14k', 'price_per_gram' => 53.96, 'price_per_ounce' => 1678.33, 'price_per_tola' => 628.50, 'source' => 'London Fix', 'is_active' => true],
            ['purity' => '10k', 'price_per_gram' => 38.54, 'price_per_ounce' => 1198.33, 'price_per_tola' => 449.50, 'source' => 'London Fix', 'is_active' => true],
        ];

        foreach ($goldPrices as $price) {
            GoldPrice::create(array_merge($price, ['effective_from' => now()]));
        }
    }

    private function createProductsForCategory(Category $category): void
    {
        $productsData = $this->getProductsDataByCategory($category->slug);

        foreach ($productsData as $productData) {
            $productData['category_id'] = $category->id;
            $productData['sku'] = strtoupper($category->slug) . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $productData['is_active'] = true;
            $productData['is_featured'] = $productData['is_featured'] ?? false;
            $productData['stock_quantity'] = $productData['stock_quantity'] ?? random_int(5, 50);
            $productData['low_stock_threshold'] = 5;
            $productData['image'] = null;
            $productData['images'] = [];

            Product::create($productData);
        }
    }

    private function getProductsDataByCategory(string $category): array
    {
        $goldPrices = [
            '24k' => 92.50,
            '22k' => 84.79,
            '18k' => 69.38,
            '14k' => 53.96,
        ];

        $products = [
            'rings' => [
                ['name' => 'خاتم أميرية', 'gold_purity' => '22k', 'weight' => 8.5, 'making_cost' => 150.00, 'is_featured' => true, 'stock_quantity' => 25],
                ['name' => 'خاتم سوداني تقليدي', 'gold_purity' => '18k', 'weight' => 12.0, 'making_cost' => 200.00, 'is_featured' => true, 'stock_quantity' => 18],
                ['name' => 'خاتم خطوبة', 'gold_purity' => '24k', 'weight' => 6.0, 'making_cost' => 300.00, 'is_featured' => true, 'stock_quantity' => 10],
                ['name' => 'خاتم زفاف', 'gold_purity' => '22k', 'weight' => 5.0, 'making_cost' => 180.00, 'is_featured' => true, 'stock_quantity' => 30],
                ['name' => 'خاتم تورمالين', 'gold_purity' => '18k', 'weight' => 9.0, 'making_cost' => 250.00, 'is_featured' => false, 'stock_quantity' => 15],
                ['name' => 'خاتم ياقوت', 'gold_purity' => '22k', 'weight' => 7.5, 'making_cost' => 400.00, 'is_featured' => true, 'stock_quantity' => 8],
                ['name' => 'خاتم أزرق', 'gold_purity' => '18k', 'weight' => 6.5, 'making_cost' => 220.00, 'is_featured' => false, 'stock_quantity' => 22],
                ['name' => 'خاتم زمرد', 'gold_purity' => '22k', 'weight' => 8.0, 'making_cost' => 350.00, 'is_featured' => true, 'stock_quantity' => 12],
            ],
            'necklaces' => [
                ['name' => 'قلادة السلطانة', 'gold_purity' => '24k', 'weight' => 25.0, 'making_cost' => 500.00, 'is_featured' => true, 'stock_quantity' => 15],
                ['name' => 'قلادة الدار', 'gold_purity' => '22k', 'weight' => 18.0, 'making_cost' => 350.00, 'is_featured' => true, 'stock_quantity' => 20],
                ['name' => 'قلادة النوبة', 'gold_purity' => '18k', 'weight' => 22.0, 'making_cost' => 400.00, 'is_featured' => true, 'stock_quantity' => 12],
                ['name' => 'قلادة مهراجا', 'gold_purity' => '24k', 'weight' => 35.0, 'making_cost' => 800.00, 'is_featured' => true, 'stock_quantity' => 6],
                ['name' => 'قلادة princess', 'gold_purity' => '22k', 'weight' => 15.0, 'making_cost' => 280.00, 'is_featured' => true, 'stock_quantity' => 25],
                ['name' => 'قلادة سلسلية', 'gold_purity' => '18k', 'weight' => 12.0, 'making_cost' => 220.00, 'is_featured' => false, 'stock_quantity' => 30],
                ['name' => 'قلادة pendant', 'gold_purity' => '22k', 'weight' => 10.0, 'making_cost' => 200.00, 'is_featured' => true, 'stock_quantity' => 18],
                ['name' => 'قلادة festival', 'gold_purity' => '18k', 'weight' => 28.0, 'making_cost' => 550.00, 'is_featured' => true, 'stock_quantity' => 10],
            ],
            'bracelets' => [
                ['name' => 'سوار ملكي', 'gold_purity' => '22k', 'weight' => 30.0, 'making_cost' => 400.00, 'is_featured' => true, 'stock_quantity' => 20],
                ['name' => 'سوار الدار', 'gold_purity' => '18k', 'weight' => 25.0, 'making_cost' => 350.00, 'is_featured' => true, 'stock_quantity' => 25],
                ['name' => 'سوار تورس', 'gold_purity' => '24k', 'weight' => 40.0, 'making_cost' => 600.00, 'is_featured' => true, 'stock_quantity' => 10],
                ['name' => 'سوار عروس', 'gold_purity' => '22k', 'weight' => 35.0, 'making_cost' => 500.00, 'is_featured' => true, 'stock_quantity' => 15],
                ['name' => 'سوار chain', 'gold_purity' => '18k', 'weight' => 18.0, 'making_cost' => 250.00, 'is_featured' => false, 'stock_quantity' => 35],
                ['name' => 'سوار tennis', 'gold_purity' => '14k', 'weight' => 20.0, 'making_cost' => 300.00, 'is_featured' => true, 'stock_quantity' => 22],
                ['name' => 'سوار bangle', 'gold_purity' => '22k', 'weight' => 28.0, 'making_cost' => 380.00, 'is_featured' => true, 'stock_quantity' => 18],
                ['name' => 'سوار charm', 'gold_purity' => '18k', 'weight' => 15.0, 'making_cost' => 220.00, 'is_featured' => false, 'stock_quantity' => 28],
            ],
            'earrings' => [
                ['name' => 'حلقات أذن ملكية', 'gold_purity' => '22k', 'weight' => 8.0, 'making_cost' => 180.00, 'is_featured' => true, 'stock_quantity' => 30],
                ['name' => 'حلقات أذن دائرية', 'gold_purity' => '18k', 'weight' => 6.0, 'making_cost' => 120.00, 'is_featured' => true, 'stock_quantity' => 40],
                ['name' => 'حلقات أذن م hanger', 'gold_purity' => '24k', 'weight' => 5.0, 'making_cost' => 150.00, 'is_featured' => true, 'stock_quantity' => 25],
                ['name' => 'حلقات أذن drops', 'gold_purity' => '22k', 'weight' => 10.0, 'making_cost' => 220.00, 'is_featured' => true, 'stock_quantity' => 20],
                ['name' => 'حلقات أذن hoops', 'gold_purity' => '18k', 'weight' => 7.0, 'making_cost' => 140.00, 'is_featured' => false, 'stock_quantity' => 35],
                ['name' => 'حلقات أذن studs', 'gold_purity' => '14k', 'weight' => 4.0, 'making_cost' => 80.00, 'is_featured' => true, 'stock_quantity' => 45],
                ['name' => 'حلقات أذن clusters', 'gold_purity' => '22k', 'weight' => 9.0, 'making_cost' => 200.00, 'is_featured' => true, 'stock_quantity' => 18],
                ['name' => 'حلقات أذن statement', 'gold_purity' => '18k', 'weight' => 12.0, 'making_cost' => 280.00, 'is_featured' => true, 'stock_quantity' => 15],
            ],
            'watches' => [
                ['name' => 'ساعة presidential', 'gold_purity' => '22k', 'weight' => 85.0, 'making_cost' => 1500.00, 'is_featured' => true, 'stock_quantity' => 5],
                ['name' => 'ساعة luxury', 'gold_purity' => '18k', 'weight' => 75.0, 'making_cost' => 1200.00, 'is_featured' => true, 'stock_quantity' => 8],
                ['name' => 'ساعة classic', 'gold_purity' => '22k', 'weight' => 65.0, 'making_cost' => 1000.00, 'is_featured' => true, 'stock_quantity' => 10],
                ['name' => 'ساعة skeleton', 'gold_purity' => '24k', 'weight' => 90.0, 'making_cost' => 2000.00, 'is_featured' => true, 'stock_quantity' => 3],
                ['name' => 'ساعة sport', 'gold_purity' => '18k', 'weight' => 70.0, 'making_cost' => 1100.00, 'is_featured' => false, 'stock_quantity' => 12],
                ['name' => 'ساعة dress', 'gold_purity' => '22k', 'weight' => 55.0, 'making_cost' => 900.00, 'is_featured' => true, 'stock_quantity' => 15],
            ],
        ];

        $result = [];
        foreach ($products[$category] as $product) {
            $purity = $product['gold_purity'];
            $goldPricePerGram = $goldPrices[$purity] ?? 69.38;
            $price = ($product['weight'] * $goldPricePerGram) + $product['making_cost'];
            
            $result[] = [
                'name' => $product['name'],
                'description' => "{$product['name']} مصنوعة من ذهب {$purity} عيار، وزن {$product['weight']} جرام",
                'gold_purity' => $purity,
                'weight' => $product['weight'],
                'making_cost' => $product['making_cost'],
                'gold_price_per_gram' => $goldPricePerGram,
                'price' => round($price, 2),
                'special_price' => null,
                'is_featured' => $product['is_featured'],
                'stock_quantity' => $product['stock_quantity'],
            ];
        }

        return $result;
    }
}
