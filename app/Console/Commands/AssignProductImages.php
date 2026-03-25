<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class AssignProductImages extends Command
{
    protected $signature = 'app:assign-product-images';

    protected $description = 'Assign specific real images to all products';

    public function handle(): int
    {
        $imageMap = [
            'rings' => [
                'خاتم أميرية' => 'https://images.unsplash.com/photo-1603974372039-adc49044b6bd?w=600&h=600&fit=crop',
                'خاتم سوداني تقليدي' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&h=600&fit=crop',
                'خاتم خطوبة' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&h=600&fit=crop',
                'خاتم زفاف' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&h=600&fit=crop',
                'خاتم تورمالين' => 'https://images.unsplash.com/photo-1603561591411-07134e71a2a9?w=600&h=600&fit=crop',
                'خاتم ياقوت' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=600&h=600&fit=crop',
                'خاتم أزرق' => 'https://images.unsplash.com/photo-1602751584552-8ba73aad10e1?w=600&h=600&fit=crop',
                'خاتم زمرد' => 'https://images.unsplash.com/photo-1603561591411-07134e71a2a9?w=600&h=600&fit=crop',
            ],
            'necklaces' => [
                'قلادة السلطانة' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&h=600&fit=crop',
                'قلادة الدار' => 'https://images.unsplash.com/photo-1611085583191-a3b181a88401?w=600&h=600&fit=crop',
                'قلادة النوبة' => 'https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=600&h=600&fit=crop',
                'قلادة مهراجا' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&h=600&fit=crop',
                'قلادة princess' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=600&h=600&fit=crop',
                'قلادة سلسلية' => 'https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=600&h=600&fit=crop',
                'قلادة pendant' => 'https://images.unsplash.com/photo-1603974372039-adc49044b6bd?w=600&h=600&fit=crop',
                'قلادة festival' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&h=600&fit=crop',
            ],
            'bracelets' => [
                'سوار ملكي' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=600&h=600&fit=crop',
                'سوار الدار' => 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=600&h=600&fit=crop',
                'سوار تورس' => 'https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=600&h=600&fit=crop',
                'سوار عروس' => 'https://images.unsplash.com/photo-1603561591411-07134e71a2a9?w=600&h=600&fit=crop',
                'سوار chain' => 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=600&h=600&fit=crop',
                'سوار tennis' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=600&h=600&fit=crop',
                'سوار bangle' => 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=600&h=600&fit=crop',
                'سوار charm' => 'https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=600&h=600&fit=crop',
            ],
            'earrings' => [
                'حلقات أذن ملكية' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&h=600&fit=crop',
                'حلقات أذن دائرية' => 'https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=600&h=600&fit=crop',
                'حلقات أذن م hanger' => 'https://images.unsplash.com/photo-1602173574767-37ac01994b2a?w=600&h=600&fit=crop',
                'حلقات أذن drops' => 'https://images.unsplash.com/photo-1599643477877-530eb83abc8e?w=600&h=600&fit=crop',
                'حلقات أذن hoops' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600&h=600&fit=crop',
                'حلقات أذن studs' => 'https://images.unsplash.com/photo-1603974372039-adc49044b6bd?w=600&h=600&fit=crop',
                'حلقات أذن clusters' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&h=600&fit=crop',
                'حلقات أذن statement' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=600&h=600&fit=crop',
            ],
            'watches' => [
                'ساعة presidential' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&h=600&fit=crop',
                'ساعة luxury' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600&h=600&fit=crop',
                'ساعة classic' => 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?w=600&h=600&fit=crop',
                'ساعة skeleton' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=600&h=600&fit=crop',
                'ساعة sport' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600&h=600&fit=crop',
                'ساعة dress' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?w=600&h=600&fit=crop',
            ],
        ];

        $products = Product::with('category')->get();
        $updated = 0;

        foreach ($products as $product) {
            $categorySlug = $product->category?->slug;
            $productName = $product->name;

            if ($categorySlug && isset($imageMap[$categorySlug])) {
                if (isset($imageMap[$categorySlug][$productName])) {
                    $product->update(['image' => $imageMap[$categorySlug][$productName]]);
                    $this->info("Updated: {$productName} ({$categorySlug})");
                    $updated++;
                } else {
                    $firstImage = reset($imageMap[$categorySlug]);
                    $product->update(['image' => $firstImage]);
                    $this->info("Updated (default): {$productName} ({$categorySlug})");
                    $updated++;
                }
            }
        }

        $this->info("Done! Updated {$updated} products with images.");

        return Command::SUCCESS;
    }
}
