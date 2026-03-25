<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoldPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoldPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage_gold_prices']);
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');

        $goldPrices = GoldPrice::with('user')
            ->when($search, fn($q) => $q->where('purity', 'like', "%{$search}%"))
            ->orderBy('purity')
            ->orderBy('effective_date', 'desc')
            ->paginate(15);

        return view('admin.gold-prices.index', compact('goldPrices', 'search'));
    }

    public function create(): View
    {
        return view('admin.gold-prices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purity' => ['required', 'string', 'max:10'],
            'price_per_gram' => ['required', 'numeric', 'min:0'],
            'price_per_ounce' => ['nullable', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['user_id'] = auth()->id();

        if ($request->boolean('set_current')) {
            GoldPrice::where('purity', $validated['purity'])->update(['is_active' => false]);
        }

        GoldPrice::create($validated);

        return redirect()->route('admin.gold-prices.index')->with('success', 'Gold price created successfully.');
    }

    public function edit(GoldPrice $goldPrice): View
    {
        return view('admin.gold-prices.edit', compact('goldPrice'));
    }

    public function update(Request $request, GoldPrice $goldPrice): RedirectResponse
    {
        $validated = $request->validate([
            'purity' => ['required', 'string', 'max:10'],
            'price_per_gram' => ['required', 'numeric', 'min:0'],
            'price_per_ounce' => ['nullable', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->boolean('set_current')) {
            GoldPrice::where('purity', $validated['purity'])->where('id', '!=', $goldPrice->id)->update(['is_active' => false]);
        }

        $goldPrice->update($validated);

        return redirect()->route('admin.gold-prices.index')->with('success', 'Gold price updated successfully.');
    }

    public function destroy(GoldPrice $goldPrice): RedirectResponse
    {
        $goldPrice->delete();

        return redirect()->route('admin.gold-prices.index')->with('success', 'Gold price deleted.');
    }

    public function setCurrent(GoldPrice $goldPrice): RedirectResponse
    {
        GoldPrice::where('purity', $goldPrice->purity)->update(['is_active' => false]);
        $goldPrice->update(['is_active' => true]);

        return redirect()->back()->with('success', 'Gold price set as current.');
    }
}
