@extends('layouts.frontend')

@section('title', __('سلة التسوق'))

@section('content')
<!-- Page Header -->
<section class="pt-32 pb-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">{{ __('Your Selection') }}</p>
            <h1 class="text-4xl md:text-6xl font-serif mb-4">{{ __('Shopping Cart') }}</h1>
            <p class="text-gray-500">{{ __('Review your selected items before checkout') }}</p>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="py-16 bg-black" x-data>
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Cart Items -->
            <div class="lg:col-span-2" x-show="$store.cart.items.length > 0">
                <div class="border-b border-gray-800 pb-4 mb-4 hidden md:grid grid-cols-12 gap-4 text-gray-500 text-sm">
                    <div class="col-span-6">{{ __('Product') }}</div>
                    <div class="col-span-2 text-center">{{ __('Price') }}</div>
                    <div class="col-span-2 text-center">{{ __('Quantity') }}</div>
                    <div class="col-span-2 text-right">{{ __('Total') }}</div>
                </div>
                
                <template x-for="item in $store.cart.items" :key="item.id">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 py-6 border-b border-gray-800 items-center">
                        <div class="md:col-span-6 flex gap-4">
                            <div class="w-24 h-24 bg-gray-900 flex-shrink-0">
                                <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="font-serif text-lg mb-1" x-text="item.name"></h3>
                                <p class="text-gray-500 text-sm mb-2" x-text="item.gold_purity + ' ذهب'"></p>
                                <button @click="$store.cart.remove(item.id)" class="text-gray-500 hover:text-red-400 text-sm transition-colors">{{ __('إزالة') }}</button>
                            </div>
                        </div>
                        <div class="md:col-span-2 text-center">
                            <span class="md:hidden text-gray-500 text-sm mr-2">{{ __('السعر') }}:</span>
                            <span class="gold-text" x-text="'$' + item.price"></span>
                        </div>
                        <div class="md:col-span-2 flex justify-center">
                            <div class="flex items-center border border-gray-700">
                                <button @click="$store.cart.updateQuantity(item.id, -1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gold transition-colors">-</button>
                                <span class="w-8 text-center text-sm" x-text="item.quantity"></span>
                                <button @click="$store.cart.updateQuantity(item.id, 1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gold transition-colors">+</button>
                            </div>
                        </div>
                        <div class="md:col-span-2 text-right">
                            <span class="md:hidden text-gray-500 text-sm mr-2">{{ __('الإجمالي') }}:</span>
                            <span class="text-lg font-medium" x-text="'$' + (item.price * item.quantity)"></span>
                        </div>
                    </div>
                </template>
                
                <div class="mt-8 flex justify-between items-center">
                    <a href="{{ route('catalog') }}" class="text-gold hover:text-gold-light transition-colors">
                        ← {{ __('الإستمرار في التسوق') }}
                    </a>
                    <button @click="$store.cart.clear()" class="text-gray-500 hover:text-red-400 transition-colors text-sm">
                        {{ __('حذف السلة') }}
                    </button>
                </div>
            </div>
            
            <!-- Empty Cart -->
            <div class="lg:col-span-2 text-center py-16" x-show="$store.cart.items.length === 0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-700 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h2 class="text-2xl font-serif mb-4">{{ __('Your Cart is Empty') }}</h2>
                <p class="text-gray-500 mb-8">{{ __('Discover our exquisite collection of fine gold jewelry') }}</p>
                <a href="{{ route('catalog') }}" class="btn-gold">{{ __('Browse Collection') }}</a>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-black-light border border-gray-800 p-6 sticky top-24">
                    <h2 class="text-xl font-serif mb-6">{{ __('Order Summary') }}</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-400">{{ __('Subtotal') }}</span>
                            <span x-text="'$' + $store.cart.total"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">{{ __('Shipping') }}</span>
                            <span class="text-gold">{{ __('Free') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">{{ __('Tax') }}</span>
                            <span x-text="'$' + Math.round($store.cart.total * 0.08)"></span>
                        </div>
                        <div class="border-t border-gray-800 pt-4 flex justify-between">
                            <span class="font-medium text-lg">{{ __('Total') }}</span>
                            <span class="text-xl gold-gradient font-serif" x-text="'$' + ($store.cart.total + Math.round($store.cart.total * 0.08))"></span>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="text-gray-400 text-sm mb-2 block">{{ __('Promo Code') }}</label>
                        <div class="flex gap-2">
                            <input type="text" placeholder="{{ __('Enter code') }}" class="input-luxury flex-1 text-sm">
                            <button class="btn-outline-gold px-4 text-sm group relative overflow-hidden">
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                {{ __('Apply') }}
                            </button>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout') }}" class="btn-gold w-full text-center block group relative overflow-hidden" :class="$store.cart.items.length === 0 ? 'pointer-events-none opacity-50' : ''">
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        <span class="sparkle"></span>
                        {{ __('Proceed to Checkout') }}
                    </a>
                    
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>{{ __('Secure Checkout') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>{{ __('Lifetime Warranty') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
