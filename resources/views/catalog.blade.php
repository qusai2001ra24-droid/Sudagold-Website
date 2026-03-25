@extends('layouts.frontend')

@section('title', 'المجموعات')

@section('styles')
<style>
    .product-card:hover .product-actions {
        opacity: 1;
        transform: translateY(0);
    }
    .product-actions {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<section class="pt-32 pb-16 bg-black relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">سوداقولد</p>
            <h1 class="text-4xl md:text-6xl font-serif mb-4">
                @if(request('category') && request('category') !== 'all')
                    {{ $categories->where('slug', request('category'))->first()?->name ?? 'المنتجات' }}
                @else
                    جميع المجوهرات
                @endif
            </h1>
            <p class="text-gray-500 max-w-2xl mx-auto">
                اكتشف مجموعة واسعة من المجوهرات الذهبية الفاخرة
            </p>
        </div>
    </div>
</section>

<!-- Filters & Products -->
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Filter Bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-12 pb-6 border-b border-gray-800">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('catalog') }}" 
                   class="px-4 py-2 text-sm border transition-all duration-300 {{ !request('category') || request('category') === 'all' ? 'border-gold text-gold bg-gold/10' : 'border-gray-700 text-gray-400 hover:border-gold hover:text-gold' }}">
                    كل المنتجات
                </a>
                @foreach($categories as $category)
                <a href="{{ route('catalog', ['category' => $category->slug]) }}" 
                   class="px-4 py-2 text-sm border transition-all duration-300 {{ request('category') === $category->slug ? 'border-gold text-gold bg-gold/10' : 'border-gray-700 text-gray-400 hover:border-gold hover:text-gold' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
            
            <form action="{{ route('catalog') }}" method="GET" class="flex items-center gap-4">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <span class="text-gray-500 text-sm hidden sm:block">ترتيب حسب:</span>
                <select name="sort" onchange="this.form.submit()" class="bg-black border border-gray-700 text-white px-4 py-2 text-sm focus:border-gold focus:outline-none rounded-lg cursor-pointer">
                    <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>المميزة أولاً</option>
                    <option value="price-low" {{ request('sort') === 'price-low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                    <option value="price-high" {{ request('sort') === 'price-high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>الاسم (أ-ي)</option>
                </select>
            </form>
        </div>
        
        <!-- Products Count -->
        <div class="mb-8 text-gray-500">
            عرض {{ $products->count() }} من {{ $products->total() }} منتج
        </div>
        
        <!-- Products Grid -->
        @if($products->isEmpty())
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-700 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-xl font-serif mb-2">لا توجد منتجات</h3>
            <p class="text-gray-500 mb-6">لم يتم العثور على منتجات في هذا التصنيف.</p>
            <a href="{{ route('catalog') }}" class="btn-outline-gold inline-block">عرض جميع المنتجات</a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="product-card card-luxury p-4 group relative">
                <!-- Product Image -->
                <a href="{{ route('product', ['id' => $product->id]) }}" class="block mb-4">
                    <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden relative">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        <!-- Badges -->
                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            @if($product->is_featured)
                            <span class="bg-gold text-black text-xs font-bold px-2 py-1 rounded">مميز</span>
                            @endif
                            @if($product->special_price)
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">خصم</span>
                            @endif
                            @if($product->is_low_stock && $product->stock_quantity > 0)
                            <span class="bg-amber-500 text-black text-xs font-bold px-2 py-1 rounded">كمية محدودة</span>
                            @endif
                        </div>

                        <!-- Quick Actions Overlay -->
                        <div class="product-actions absolute bottom-4 left-4 right-4">
                            <button @click.prevent="$store.cart.add({{ $product->id }}, 1); $store.cart.open();" class="w-full bg-gold text-black font-medium py-3 rounded-lg hover:bg-gold-light transition-colors flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                أضف للسلة
                            </button>
                        </div>
                    </div>
                </a>

                <!-- Product Info -->
                <div>
                    @if($product->category)
                    <p class="text-gold text-xs uppercase tracking-wider mb-1">{{ $product->category->name }}</p>
                    @endif
                    <a href="{{ route('product', ['id' => $product->id]) }}" class="block">
                        <h3 class="font-serif text-lg mb-1 group-hover:text-gold transition-colors">{{ $product->name }}</h3>
                    </a>
                    <p class="text-gray-500 text-sm mb-2">{{ $product->gold_purity }} • {{ $product->weight }}g</p>
                    
                    <!-- Price -->
                    <div class="flex items-center gap-2">
                        @if($product->special_price)
                        <span class="gold-text font-bold text-lg">${{ number_format($product->special_price, 2) }}</span>
                        <span class="text-gray-600 text-sm line-through">${{ number_format($product->price, 2) }}</span>
                        @else
                        <span class="gold-text font-bold text-lg">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-16 flex justify-center">
            <div class="flex items-center gap-2">
                @if($products->onFirstPage())
                    <span class="w-10 h-10 flex items-center justify-center text-gray-600 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-gold hover:text-gold transition-colors rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif

                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center bg-gold text-black font-medium rounded-lg">{{ $page }}</span>
                    @elseif($page >= $products->currentPage() - 1 && $page <= $products->currentPage() + 1)
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-gold hover:text-gold transition-colors rounded-lg">{{ $page }}</a>
                    @endif
                @endforeach

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-gray-700 text-gray-400 hover:border-gold hover:text-gold transition-colors rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @else
                    <span class="w-10 h-10 flex items-center justify-center text-gray-600 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
        @endif
        @endif
    </div>
</section>
@endsection
