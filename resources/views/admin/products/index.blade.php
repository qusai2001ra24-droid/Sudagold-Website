@extends('admin.layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')

@section('header_actions')
@can('create_products')
<a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-[#d4af37] text-black text-sm font-medium rounded-lg hover:bg-[#e8c547] transition-colors">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    Add Product
</a>
@endcan
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <!-- Filters -->
    <div class="p-6 border-b border-gray-200">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search products..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
            </div>
            <div class="w-48">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                    <option value="">All Status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                Filter
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Clear
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gold Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ Storage::url('products/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                @if($product->is_featured)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-[#d4af37]/10 text-[#d4af37] rounded">Featured</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->sku }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category?->name }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">{{ $product->gold_purity }}</span>
                            <p class="text-gray-500 mt-1">{{ $product->weight }}g</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            @if($product->special_price)
                                <p class="font-medium text-[#d4af37]">${{ number_format($product->special_price, 2) }}</p>
                                <p class="text-gray-400 line-through text-xs">${{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="font-medium text-gray-900">${{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <p class="font-medium {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock_quantity }}
                            </p>
                            @if($product->stock_quantity <= $product->low_stock_threshold)
                                <p class="text-xs text-red-500">Low Stock</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @can('edit_products')
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-[#d4af37] transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            @endcan
                            @can('delete_products')
                            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p>No products found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $products->links() }}
    </div>
</div>
@endsection
