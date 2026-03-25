<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GoldPrice;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_products,create_products,edit_products,delete_products']);
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');
        $categoryId = $request->get('category');
        $status = $request->get('status');

        $products = Product::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($status !== null && $status !== '', fn($q) => $q->where('is_active', $status === 'active'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId', 'status'));
    }

    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $goldPurities = ['24k', '22k', '18k', '14k', '10k'];
        $currentGoldPrices = GoldPrice::current()->get()->keyBy('purity');

        return view('admin.products.create', compact('categories', 'goldPurities', 'currentGoldPrices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'gold_purity' => ['required', 'in:24k,22k,18k,14k,10k'],
            'weight' => ['required', 'numeric', 'min:0.001', 'max:9999.999'],
            'making_cost' => ['required', 'numeric', 'min:0'],
            'gold_price_per_gram' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'special_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadImage($request->file('image'));
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $this->uploadImage($image);
            }
            $validated['images'] = $images;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $goldPurities = ['24k', '22k', '18k', '14k', '10k'];
        $currentGoldPrices = GoldPrice::current()->get()->keyBy('purity');

        return view('admin.products.edit', compact('product', 'categories', 'goldPurities', 'currentGoldPrices'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'gold_purity' => ['required', 'in:24k,22k,18k,14k,10k'],
            'weight' => ['required', 'numeric', 'min:0.001', 'max:9999.999'],
            'making_cost' => ['required', 'numeric', 'min:0'],
            'gold_price_per_gram' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'special_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $validated['image'] = $this->uploadImage($request->file('image'));
        }

        if ($request->hasFile('images')) {
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    $this->deleteImage($oldImage);
                }
            }
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $this->uploadImage($image);
            }
            $validated['images'] = $images;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            $this->deleteImage($product->image);
        }

        if ($product->images) {
            foreach ($product->images as $image) {
                $this->deleteImage($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'stock_quantity' => ['required', 'integer'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $oldQuantity = $product->stock_quantity;
        $newQuantity = $request->input('stock_quantity');
        $change = $newQuantity - $oldQuantity;

        $product->update(['stock_quantity' => $newQuantity]);

        $product->inventoryMovements()->create([
            'user_id' => auth()->id(),
            'movement_type' => $change >= 0 ? 'adjustment' : 'adjustment',
            'quantity_change' => $change,
            'quantity_before' => $oldQuantity,
            'quantity_after' => $newQuantity,
            'reason' => $request->input('reason', 'Stock update via admin'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Stock updated successfully.');
    }

    public function calculatePrice(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'gold_purity' => ['required', 'in:24k,22k,18k,14k,10k'],
            'weight' => ['required', 'numeric', 'min:0.001'],
            'making_cost' => ['required', 'numeric', 'min:0'],
            'gold_price_per_gram' => ['required', 'numeric', 'min:0'],
        ]);

        $goldValue = $validated['weight'] * $validated['gold_price_per_gram'];
        $finalPrice = $goldValue + $validated['making_cost'];

        return response()->json([
            'gold_value' => round($goldValue, 2),
            'making_cost' => round($validated['making_cost'], 2),
            'calculated_price' => round($finalPrice, 2),
        ]);
    }

    private function uploadImage($image): string
    {
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('products', $filename, 'public');

        return $filename;
    }

    private function deleteImage(?string $filename): void
    {
        if ($filename && Storage::disk('public')->exists('products/' . $filename)) {
            Storage::disk('public')->delete('products/' . $filename);
        }
    }
}
