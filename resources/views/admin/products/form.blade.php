@extends('admin.layouts.app')

@section('title', $product ? 'Edit Product' : 'Create Product')
@section('page_title', $product ? 'Edit Product' : 'Create Product')

@section('content')
<form method="POST" action="{{ $product ? route('admin.products.update', $product->id) : route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($product) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product?->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                        <input type="text" name="sku" value="{{ old('sku', $product?->sku) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                        @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">{{ old('description', $product?->description) }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product?->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Gold Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gold Specific Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gold Purity *</label>
                        <select name="gold_purity" id="gold_purity" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]" onchange="updateGoldPrice()">
                            @foreach($goldPurities as $purity)
                                <option value="{{ $purity }}" {{ old('gold_purity', $product?->gold_purity) == $purity ? 'selected' : '' }}>
                                    {{ $purity }}
                                </option>
                            @endforeach
                        </select>
                        @error('gold_purity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (grams) *</label>
                        <input type="number" step="0.001" name="weight" id="weight" value="{{ old('weight', $product?->weight) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]" oninput="calculatePrice()">
                        @error('weight') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gold Price per Gram ($) *</label>
                        <input type="number" step="0.01" name="gold_price_per_gram" id="gold_price_per_gram" value="{{ old('gold_price_per_gram', $product?->gold_price_per_gram ?? $currentGoldPrices['24k']->price_per_gram ?? 0) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]" oninput="calculatePrice()">
                        @error('gold_price_per_gram') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-500">Current: @foreach($currentGoldPrices as $p => $gp){{ $p }}: ${{ number_format($gp->price_per_gram, 2) }} @endforeach</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Making Cost ($) *</label>
                        <input type="number" step="0.01" name="making_cost" id="making_cost" value="{{ old('making_cost', $product?->making_cost) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]" oninput="calculatePrice()">
                        @error('making_cost') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Price Calculation Preview -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Price Calculation Preview</h4>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Gold Value</p>
                            <p class="font-semibold text-gray-900" id="gold_value">$0.00</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Making Cost</p>
                            <p class="font-semibold text-gray-900" id="making_cost_display">$0.00</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Calculated Price</p>
                            <p class="font-semibold text-[#d4af37]" id="calculated_price">$0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Regular Price ($) *</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product?->price) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Special Price ($)</label>
                        <input type="number" step="0.01" name="special_price" value="{{ old('special_price', $product?->special_price) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                        @error('special_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product?->stock_quantity ?? 0) }}" required min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                        @error('stock_quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product?->low_stock_threshold ?? 5) }}" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                        @error('low_stock_threshold') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h3>
                @if($product?->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url('products/' . $product->image) }}" alt="Current Image" class="w-full h-48 object-cover rounded-lg">
                        <p class="text-sm text-gray-500 mt-2">Current image</p>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload New Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-500">Max 5MB. JPEG, PNG, JPG, GIF, WEBP</p>
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery Images</h3>
                @if($product?->images)
                    <div class="mb-4 grid grid-cols-3 gap-2">
                        @foreach($product->images as $img)
                            <img src="{{ Storage::url('products/' . $img) }}" class="w-full h-20 object-cover rounded">
                        @endforeach
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gallery (max 5)</label>
                    <input type="file" name="images[]" accept="image/*" multiple
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                    @error('images') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product?->is_active ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-[#d4af37] focus:ring-[#d4af37]">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product?->is_featured) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-[#d4af37] focus:ring-[#d4af37]">
                        <span class="ml-2 text-sm text-gray-700">Featured Product</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full px-4 py-3 bg-[#d4af37] text-black font-medium rounded-lg hover:bg-[#e8c547] transition-colors">
                    {{ $product ? 'Update Product' : 'Create Product' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="w-full px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function updateGoldPrice() {
    const purity = document.getElementById('gold_purity').value;
    const prices = @json($currentGoldPrices->keyBy('purity')->toArray());
    
    if (prices[purity]) {
        document.getElementById('gold_price_per_gram').value = prices[purity].price_per_gram;
    }
    calculatePrice();
}

function calculatePrice() {
    const weight = parseFloat(document.getElementById('weight').value) || 0;
    const goldPrice = parseFloat(document.getElementById('gold_price_per_gram').value) || 0;
    const makingCost = parseFloat(document.getElementById('making_cost').value) || 0;
    
    const goldValue = weight * goldPrice;
    const finalPrice = goldValue + makingCost;
    
    document.getElementById('gold_value').textContent = '$' + goldValue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('making_cost_display').textContent = '$' + makingCost.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('calculated_price').textContent = '$' + finalPrice.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

document.addEventListener('DOMContentLoaded', function() {
    calculatePrice();
});
</script>
@endpush
@endsection
