<?php

namespace App\Http\Controllers;

use App\Models\GoldPrice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoldAnalyticsController extends Controller
{
    public function index(): View
    {
        $goldPrices = GoldPrice::active()
            ->current()
            ->orderBy('purity', 'desc')
            ->get();

        $priceHistory = GoldPrice::orderBy('effective_from', 'desc')
            ->limit(30)
            ->get()
            ->groupBy('purity');

        $stats = [
            'total_products' => Product::active()->count(),
            'avg_gold_price' => GoldPrice::active()->current()->avg('price_per_gram'),
            'highest_purity' => GoldPrice::active()->current()->max('price_per_gram'),
            'lowest_purity' => GoldPrice::active()->current()->min('price_per_gram'),
        ];

        return view('gold-analytics', compact('goldPrices', 'priceHistory', 'stats'));
    }

    public function apiPrices()
    {
        $goldPrices = GoldPrice::active()
            ->current()
            ->orderBy('purity', 'desc')
            ->get();

        return response()->json([
            'prices' => $goldPrices,
            'updated_at' => now()->toIso8601String(),
        ]);
    }
}
