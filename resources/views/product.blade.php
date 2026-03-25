@extends('layouts.frontend')

@section('title', $product->name)

@push('styles')
<style>
    .product-image-container {
        position: relative;
        overflow: hidden;
        cursor: zoom-in;
    }
    .product-image-container img {
        transition: transform 0.5s ease;
    }
    .product-image-container:hover img {
        transform: scale(1.1);
    }
    .spec-card {
        @apply bg-gray-900/50 border border-gray-800 rounded-xl p-4 text-center;
    }
    .add-to-cart-btn {
        @apply relative overflow-hidden;
    }
    .add-to-cart-btn.loading {
        @apply pointer-events-none;
    }
    .add-to-cart-btn.loading::after {
        content: '';
        @apply absolute inset-0 bg-white/20;
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
@endpush

@section('content')
<!-- Breadcrumb -->
<section class="pt-24 pb-8 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <nav class="flex items-center gap-2 text-sm text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-gold transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('catalog') }}" class="hover:text-gold transition-colors">المجموعات</a>
            @if($product->category)
            <span>/</span>
            <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-gold transition-colors">{{ $product->category->name }}</a>
            @endif
            <span>/</span>
            <span class="text-gold">{{ $product->name }}</span>
        </nav>
    </div>
</section>

<!-- Product Details -->
<section class="py-8 bg-black" x-data="{ 
    quantity: 1,
    addedToCart: false,
    addingToCart: false
}">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="product-image-container aspect-square bg-gray-900 rounded-2xl overflow-hidden">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            
            <!-- Product Info -->
            <div>
                <!-- Category Badge -->
                @if($product->category)
                <span class="inline-block px-3 py-1 bg-gold/10 text-gold text-sm rounded-full mb-4">
                    {{ $product->category->name }}
                </span>
                @endif

                <!-- Title & Price -->
                <h1 class="text-3xl md:text-4xl font-serif mb-4">{{ $product->name }}</h1>
                
                <div class="flex items-baseline gap-4 mb-6">
                    <span class="text-3xl gold-gradient font-serif">${{ number_format($product->current_price, 2) }}</span>
                    @if($product->special_price)
                    <span class="text-gray-500 line-through text-lg">${{ number_format($product->price, 2) }}</span>
                    @php $discount = round((($product->price - $product->special_price) / $product->price) * 100); @endphp
                    <span class="bg-red-500/20 text-red-400 px-3 py-1 text-sm rounded-full">خصم {{ $discount }}%</span>
                    @endif
                </div>

                <!-- Quick Specs -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="spec-card">
                        <p class="text-gold text-2xl font-serif">{{ $product->gold_purity }}</p>
                        <p class="text-gray-500 text-sm mt-1">القيراط</p>
                    </div>
                    <div class="spec-card">
                        <p class="text-white text-2xl font-serif">{{ $product->weight }}g</p>
                        <p class="text-gray-500 text-sm mt-1">الوزن</p>
                    </div>
                    <div class="spec-card">
                        <p class="text-white text-2xl font-serif">{{ $product->sku }}</p>
                        <p class="text-gray-500 text-sm mt-1">رمز المنتج</p>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-gray-400 mb-8 leading-relaxed">
                    {{ $product->description ?? 'تحفة فنية من الذهب النقي عيار ' . $product->gold_purity . '. مصنوعة بعناية فائقة من قبل حرفيينا الماهرين.' }}
                </p>

                <!-- Price Breakdown -->
                <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 mb-8">
                    <h3 class="text-white font-medium mb-4">تفاصيل السعر</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">سعر الذهب ({{ $product->weight }}g × ${{ number_format($product->gold_price_per_gram, 2) }}/g)</span>
                            <span class="text-white">${{ number_format($product->weight * $product->gold_price_per_gram, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">تكلفة التصنيع</span>
                            <span class="text-white">${{ number_format($product->making_cost, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-700 pt-2 flex justify-between font-medium">
                            <span class="text-white">السعر الإجمالي</span>
                            <span class="gold-text">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-6">
                    @if($product->stock_quantity > 0)
                        @if($product->is_low_stock)
                        <div class="flex items-center gap-2 text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>كمية محدودة - فقط {{ $product->stock_quantity }} متوفر</span>
                        </div>
                        @else
                        <div class="flex items-center gap-2 text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>متوفر في المخزون</span>
                        </div>
                        @endif
                    @else
                    <div class="flex items-center gap-2 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>نفذت الكمية</span>
                    </div>
                    @endif
                </div>

                <!-- Quantity & Add to Cart -->
                @if($product->stock_quantity > 0)
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <!-- Quantity Selector -->
                    <div class="flex items-center border border-gray-700 rounded-lg overflow-hidden">
                        <button @click="quantity > 1 && quantity--" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-gold hover:bg-gray-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        <span x-text="quantity" class="w-16 text-center text-lg font-medium"></span>
                        <button @click="quantity++" class="w-12 h-12 flex items-center justify-center text-gray-400 hover:text-gold hover:bg-gray-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>

                    <!-- Add to Cart Button -->
                    <button 
                        x-on:click="
                            addingToCart = true;
                            const result = await $store.cart.add({{ $product->id }}, quantity);
                            addingToCart = false;
                            if (result.success) {
                                addedToCart = true;
                                setTimeout(() => addedToCart = false, 3000);
                            }
                        "
                        :class="addingToCart ? 'loading' : ''"
                        class="btn-gold flex-1 flex items-center justify-center gap-2 h-12"
                    >
                        <template x-if="!addingToCart && !addedToCart">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                إضافة للسلة
                            </span>
                        </template>
                        <template x-if="addingToCart">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                جاري الإضافة...
                            </span>
                        </template>
                        <template x-if="addedToCart">
                            <span class="flex items-center gap-2 text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                تمت الإضافة!
                            </span>
                        </template>
                    </button>

                    <!-- Wishlist Button -->
                    <button class="w-12 h-12 border border-gray-700 flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>
                @else
                <button disabled class="w-full h-12 bg-gray-800 text-gray-500 rounded-lg cursor-not-allowed mb-8">
                    نفذت الكمية - سيتم التوفر قريباً
                </button>
                @endif

                <!-- Features -->
                <div class="grid grid-cols-2 gap-4 py-6 border-t border-gray-800">
                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span class="text-gray-400">شحن مجاني</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-gray-400">ضمان أصلي</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-gray-400">إرجاع خلال 30 يوم</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-gray-400">توصيل خلال 3-5 أيام</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="py-12 bg-black-light">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-gray-900/50 border border-gray-800 rounded-2xl overflow-hidden">
            <!-- Tabs Header -->
            <div class="flex border-b border-gray-800">
                <button class="tab-btn active flex-1 py-4 text-center transition-colors" data-tab="details">
                    تفاصيل المنتج
                </button>
                <button class="tab-btn flex-1 py-4 text-center transition-colors" data-tab="shipping">
                    معلومات الشحن
                </button>
                <button class="tab-btn flex-1 py-4 text-center transition-colors" data-tab="care">
                    العناية بالمجوهرات
                </button>
            </div>

            <!-- Tabs Content -->
            <div class="p-8">
                <div id="tab-details" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-serif mb-4">مواصفات المنتج</h3>
                            <table class="w-full">
                                <tbody class="divide-y divide-gray-800">
                                    <tr>
                                        <td class="py-3 text-gray-400">المادة</td>
                                        <td class="py-3 text-white">ذهب نقي {{ $product->gold_purity }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-400">الوزن</td>
                                        <td class="py-3 text-white">{{ $product->weight }} جرام</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-400">سعر الذهب لكل جرام</td>
                                        <td class="py-3 text-white">${{ number_format($product->gold_price_per_gram, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-400">تكلفة التصنيع</td>
                                        <td class="py-3 text-white">${{ number_format($product->making_cost, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 text-gray-400">رمز المنتج</td>
                                        <td class="py-3 text-white">{{ $product->sku }}</td>
                                    </tr>
                                    @if($product->category)
                                    <tr>
                                        <td class="py-3 text-gray-400">الفئة</td>
                                        <td class="py-3 text-white">{{ $product->category->name }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h3 class="text-xl font-serif mb-4">وصف المنتج</h3>
                            <p class="text-gray-400 leading-relaxed">
                                {{ $product->description ?? 'تحفة فنية من الذهب النقي عيار ' . $product->gold_purity . '. تتميز هذه القطعة بتصميم أنيق وعصري، مصنوعة بعناية فائقة من قبل حرفيينا الماهرين في سوداقولد. كل قطعة تمر بمراحل متعددة من الفحص والتدقيق لضمان أعلى معايير الجودة.' }}
                            </p>
                            <p class="text-gray-400 leading-relaxed mt-4">
                                هذه المجوهرات مناسبة للمناسبات الخاصة واليومية على حد سواء، وتأتي مع شهادة أصالة وضمان مدى الحياة.
                            </p>
                        </div>
                    </div>
                </div>

                <div id="tab-shipping" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center p-6 bg-black/30 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gold mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <h4 class="text-white font-medium mb-2">شحن مجاني</h4>
                            <p class="text-gray-400 text-sm">شحن مجاني لجميع الطلبات حول العالم</p>
                        </div>
                        <div class="text-center p-6 bg-black/30 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gold mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <h4 class="text-white font-medium mb-2">توصيل مؤمن</h4>
                            <p class="text-gray-400 text-sm">تغليف خاص مع تتبع الشحنة</p>
                        </div>
                        <div class="text-center p-6 bg-black/30 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gold mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <h4 class="text-white font-medium mb-2">إرجاع سهل</h4>
                            <p class="text-gray-400 text-sm">إرجاع مجاني خلال 30 يوم</p>
                        </div>
                    </div>
                    <div class="mt-8 p-6 bg-black/30 rounded-xl">
                        <h4 class="text-white font-medium mb-4">معلومات التوصيل</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li>• وقت التجهيز: 1-2 يوم عمل</li>
                            <li>• مدة التوصيل: 3-5 أيام عمل (داخل السودان)</li>
                            <li>• 7-14 يوم عمل (دولي)</li>
                            <li>• جميع الشحنات مؤمنة ومغلظة بعناية</li>
                        </ul>
                    </div>
                </div>

                <div id="tab-care" class="tab-content hidden">
                    <div class="space-y-6">
                        <div class="flex gap-4 p-6 bg-black/30 rounded-xl">
                            <div class="w-12 h-12 bg-gold/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-gold text-xl">✓</span>
                            </div>
                            <div>
                                <h4 class="text-white font-medium mb-2">ما يجب فعله</h4>
                                <ul class="text-gray-400 text-sm space-y-1">
                                    <li>• نظف المجوهرات بقطعة قماش ناعمة</li>
                                    <li>• تخزين في مكان جاف وبارد</li>
                                    <li>• أزل المجوهرات قبل السباحة</li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex gap-4 p-6 bg-black/30 rounded-xl">
                            <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-red-400 text-xl">✗</span>
                            </div>
                            <div>
                                <h4 class="text-white font-medium mb-2">ما يجب تجنبه</h4>
                                <ul class="text-gray-400 text-sm space-y-1">
                                    <li>• المواد الكيميائية والعطور</li>
                                    <li>• الماء الساخن والصابون</li>
                                    <li>• الخدش مع المجوهرات الأخرى</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->isNotEmpty())
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-serif mb-8">منتجات مشابهة</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <a href="{{ route('product', ['id' => $related->id]) }}" class="card-luxury p-4 group block">
                <div class="aspect-square bg-gray-900 rounded-xl overflow-hidden mb-4">
                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <h3 class="font-serif text-lg mb-1 group-hover:text-gold transition-colors">{{ $related->name }}</h3>
                <p class="text-gray-500 text-sm mb-2">{{ $related->gold_purity }} • {{ $related->weight }}g</p>
                <span class="gold-text font-medium">${{ number_format($related->current_price, 2) }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            tabBtns.forEach(b => {
                b.classList.remove('text-gold', 'border-b-2', 'border-gold');
                b.classList.add('text-gray-400');
            });
            
            this.classList.remove('text-gray-400');
            this.classList.add('text-gold', 'border-b-2', 'border-gold');

            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            document.getElementById('tab-' + tabId).classList.remove('hidden');
        });
    });

    tabBtns[0].click();
});
</script>
@endpush
