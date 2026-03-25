@extends('layouts.frontend')

@section('title', __('صفحة الدفع الآمن'))

@section('content')
<!-- Page Header -->
<section class="pt-32 pb-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">{{ __('Secure Checkout') }}</p>
            <h1 class="text-4xl md:text-6xl font-serif mb-4">{{ __('Complete Your Order') }}</h1>
            <p class="text-gray-500">{{ __('Finalize your purchase securely') }}</p>
        </div>
    </div>
</section>

<!-- Checkout Section -->
<section class="py-16 bg-black" x-data="{ 
    step: 1,
    sameAsBilling: true,
    paymentMethod: 'credit_card'
}">
    <div class="max-w-7xl mx-auto px-6">
        <!-- Progress Steps -->
        <div class="flex justify-center mb-12">
            <div class="flex items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-medium" :class="step >= 1 ? 'bg-[#d4af37] text-black' : 'border border-gray-700 text-gray-500'">1</div>
                    <span class="ml-2" :class="step >= 1 ? 'text-white' : 'text-gray-500'">{{ __('Shipping') }}</span>
                </div>
                <div class="w-16 h-px bg-gray-700 mx-4"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-medium" :class="step >= 2 ? 'bg-[#d4af37] text-black' : 'border border-gray-700 text-gray-500'">2</div>
                    <span class="ml-2" :class="step >= 2 ? 'text-white' : 'text-gray-500'">{{ __('Payment Method') }}</span>
                </div>
                <div class="w-16 h-px bg-gray-700 mx-4"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-medium" :class="step >= 3 ? 'bg-[#d4af37] text-black' : 'border border-gray-700 text-gray-500'">3</div>
                    <span class="ml-2" :class="step >= 3 ? 'text-white' : 'text-gray-500'">{{ __('Order Review') }}</span>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            @csrf
            <!-- Form -->
            <div class="lg:col-span-2">
                <!-- Step 1: Shipping -->
                <div x-show="step === 1">
                    <h2 class="text-2xl font-serif mb-6">{{ __('Shipping Information') }}</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('First Name') }} *</label>
                                <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name', auth()->user()->first_name ?? '') }}" required
                                    class="input-luxury w-full" placeholder="{{ __('First Name') }}">
                                @error('shipping_first_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('Last Name') }} *</label>
                                <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name', auth()->user()->last_name ?? '') }}" required
                                    class="input-luxury w-full" placeholder="{{ __('Last Name') }}">
                                @error('shipping_last_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Email Address') }} *</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email ?? '') }}" required
                                class="input-luxury w-full" placeholder="{{ __('Email Address') }}">
                            @error('shipping_email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Phone') }} *</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" required
                                class="input-luxury w-full" placeholder="{{ __('Phone') }}">
                            @error('shipping_phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Address') }} *</label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required
                                class="input-luxury w-full" placeholder="{{ __('Address') }}">
                            @error('shipping_address') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('City') }} *</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required
                                    class="input-luxury w-full" placeholder="{{ __('City') }}">
                                @error('shipping_city') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('State') }} *</label>
                                <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" required
                                    class="input-luxury w-full" placeholder="{{ __('State') }}">
                                @error('shipping_state') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('Zip Code') }} *</label>
                                <input type="text" name="shipping_zip_code" value="{{ old('shipping_zip_code') }}" required
                                    class="input-luxury w-full" placeholder="{{ __('Zip Code') }}">
                                @error('shipping_zip_code') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Country') }} *</label>
                            <select name="shipping_country" required class="input-luxury w-full">
                                <option value="">{{ __('Choose Country') }}</option>
                                <option value="Sudan" {{ old('shipping_country') == 'Sudan' ? 'selected' : '' }}>{{ __('Sudan') }}</option>
                                <option value="United States" {{ old('shipping_country') == 'United States' ? 'selected' : '' }}>{{ __('United States') }}</option>
                                <option value="United Kingdom" {{ old('shipping_country') == 'United Kingdom' ? 'selected' : '' }}>{{ __('United Kingdom') }}</option>
                                <option value="Canada" {{ old('shipping_country') == 'Canada' ? 'selected' : '' }}>{{ __('Canada') }}</option>
                                <option value="Australia" {{ old('shipping_country') == 'Australia' ? 'selected' : '' }}>{{ __('Australia') }}</option>
                                <option value="Germany" {{ old('shipping_country') == 'Germany' ? 'selected' : '' }}>{{ __('Germany') }}</option>
                                <option value="France" {{ old('shipping_country') == 'France' ? 'selected' : '' }}>{{ __('France') }}</option>
                                <option value="UAE" {{ old('shipping_country') == 'UAE' ? 'selected' : '' }}>{{ __('UAE') }}</option>
                                <option value="Saudi Arabia" {{ old('shipping_country') == 'Saudi Arabia' ? 'selected' : '' }}>{{ __('Saudi Arabia') }}</option>
                                <option value="Egypt" {{ old('shipping_country') == 'Egypt' ? 'selected' : '' }}>{{ __('Egypt') }}</option>
                            </select>
                            @error('shipping_country') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <button type="button" @click="step = 2" class="btn-gold">{{ __('Continue to Payment') }}</button>
                    </div>
                </div>
                
                <!-- Step 2: Payment -->
                <div x-show="step === 2" style="display: none;">
                    <h2 class="text-2xl font-serif mb-6">{{ __('Payment Method') }}</h2>
                    
                    <div class="mb-6">
                        <div class="border border-gray-800 p-4 mb-4 cursor-pointer transition-colors" :class="paymentMethod === 'credit_card' ? 'border-[#d4af37]' : 'hover:border-[#d4af37]/50'" @click="paymentMethod = 'credit_card'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" id="card" value="credit_card" x-model="paymentMethod" class="w-4 h-4 text-[#d4af37]">
                                <label for="card" class="flex items-center gap-2 cursor-pointer">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"><rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M2 10h20" stroke="currentColor" stroke-width="1.5"/></svg>
                                    <span>{{ __('Credit / Debit Card') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="border border-gray-800 p-4 mb-4 cursor-pointer transition-colors" :class="paymentMethod === 'paypal' ? 'border-[#d4af37]' : 'hover:border-[#d4af37]/50'" @click="paymentMethod = 'paypal'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" id="paypal" value="paypal" x-model="paymentMethod" class="w-4 h-4 text-[#d4af37]">
                                <label for="paypal" class="cursor-pointer">
                                    <span>{{ __('Paypal') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="border border-gray-800 p-4 cursor-pointer transition-colors" :class="paymentMethod === 'bank_transfer' ? 'border-[#d4af37]' : 'hover:border-[#d4af37]/50'" @click="paymentMethod = 'bank_transfer'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" id="bank" value="bank_transfer" x-model="paymentMethod" class="w-4 h-4 text-[#d4af37]">
                                <label for="bank" class="cursor-pointer">
                                    <span>{{ __('Bank Transfer') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="paymentMethod === 'credit_card'" class="space-y-6 mb-6">
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Card Number') }}</label>
                            <input type="text" class="input-luxury w-full" placeholder="{{ __('Card Number') }}">
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('Expiry Date') }}</label>
                                <input type="text" class="input-luxury w-full" placeholder="{{ __('Expiry Date') }}">
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('CVV') }}</label>
                                <input type="text" class="input-luxury w-full" placeholder="{{ __('CVV') }}">
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Name on Card') }}</label>
                            <input type="text" class="input-luxury w-full" placeholder="{{ __('Name on Card') }}">
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 mb-6">
                        <input type="checkbox" id="same_as_billing" name="same_as_billing" value="1" x-model="sameAsBilling" class="w-4 h-4 bg-black border-gray-700 rounded focus:ring-[#d4af37]">
                        <label for="same_as_billing" class="text-gray-400 text-sm">{{ __('Billing address same as shipping') }}</label>
                    </div>
                    
                    <div x-show="!sameAsBilling" class="space-y-6 mb-6 p-6 bg-[#1a1a1a] rounded-lg border border-gray-800">
                        <h3 class="font-medium mb-4">{{ __('Billing Address') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('First Name') }} *</label>
                                <input type="text" name="billing_first_name" value="{{ old('billing_first_name') }}" x-bind:required="!sameAsBilling"
                                    class="input-luxury w-full" placeholder="{{ __('First Name') }}">
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('Last Name') }} *</label>
                                <input type="text" name="billing_last_name" value="{{ old('billing_last_name') }}" x-bind:required="!sameAsBilling"
                                    class="input-luxury w-full" placeholder="{{ __('Last Name') }}">
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Address') }} *</label>
                            <input type="text" name="billing_address" value="{{ old('billing_address') }}" x-bind:required="!sameAsBilling"
                                class="input-luxury w-full" placeholder="{{ __('Address') }}">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('City') }} *</label>
                                <input type="text" name="billing_city" value="{{ old('billing_city') }}" x-bind:required="!sameAsBilling"
                                    class="input-luxury w-full" placeholder="{{ __('City') }}">
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('State') }} *</label>
                                <input type="text" name="billing_state" value="{{ old('billing_state') }}" x-bind:required="!sameAsBilling"
                                    class="input-luxury w-full" placeholder="{{ __('State') }}">
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">{{ __('Zip Code') }} *</label>
                                <input type="text" name="billing_zip_code" value="{{ old('billing_zip_code') }}" x-bind:required="!sameAsBilling"
                                    class="input-luxury w-full" placeholder="{{ __('Zip Code') }}">
                            </div>
                        </div>
                        <div>
                            <label class="text-gray-400 text-sm mb-2 block">{{ __('Country') }} *</label>
                            <select name="billing_country" x-bind:required="!sameAsBilling" class="input-luxury w-full">
                                <option value="">{{ __('Choose Country') }}</option>
                                <option value="Sudan">{{ __('Sudan') }}</option>
                                <option value="United States">{{ __('United States') }}</option>
                                <option value="United Kingdom">{{ __('United Kingdom') }}</option>
                                <option value="Canada">{{ __('Canada') }}</option>
                                <option value="Australia">{{ __('Australia') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="button" @click="step = 1" class="btn-outline-gold">{{ __('Back') }}</button>
                        <button type="button" @click="step = 3" class="btn-gold">{{ __('Review Order') }}</button>
                    </div>
                </div>
                
                <!-- Step 3: Confirmation -->
                <div x-show="step === 3" style="display: none;">
                    <h2 class="text-2xl font-serif mb-6">{{ __('Order Review') }}</h2>
                    
                    <div class="bg-[#1a1a1a] border border-gray-800 p-6 mb-6">
                        <h3 class="font-medium mb-4">{{ __('Shipping Information') }}</h3>
                        <p class="text-gray-400">{{ old('shipping_first_name') }} {{ old('shipping_last_name') }}</p>
                        <p class="text-gray-400">{{ old('shipping_address') }}</p>
                        <p class="text-gray-400">{{ old('shipping_city') }}, {{ old('shipping_state') }} {{ old('shipping_zip_code') }}</p>
                        <p class="text-gray-400">{{ old('shipping_country') }}</p>
                        <button type="button" @click="step = 1" class="text-[#d4af37] text-sm mt-2 hover:text-[#e8c547]">{{ __('View Details') }}</button>
                    </div>
                    
                    <div class="bg-[#1a1a1a] border border-gray-800 p-6 mb-6">
                        <h3 class="font-medium mb-4">{{ __('Payment Method') }}</h3>
                        <p class="text-gray-400" x-text="paymentMethod === 'credit_card' ? '{{ __('Credit / Debit Card') }}' : (paymentMethod === 'paypal' ? '{{ __('Paypal') }}' : '{{ __('Bank Transfer') }}')"></p>
                        <button type="button" @click="step = 2" class="text-[#d4af37] text-sm mt-2 hover:text-[#e8c547]">{{ __('View Details') }}</button>
                    </div>
                    
                    <div class="bg-[#1a1a1a] border border-gray-800 p-6 mb-6">
                        <h3 class="font-medium mb-4">{{ __('Items') }}</h3>
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex gap-4 py-3 border-b border-gray-800 last:border-0">
                                <div class="w-16 h-16 bg-gray-900">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium" x-text="item.name"></h4>
                                    <p class="text-gray-500 text-sm">{{ __('Quantity') }}: <span x-text="item.quantity"></span></p>
                                </div>
                                <span x-text="'$' + (item.price * item.quantity)"></span>
                            </div>
                        </template>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="button" @click="step = 2" class="btn-outline-gold">{{ __('Back') }}</button>
                        <button type="submit" class="btn-gold flex-1">{{ __('Place Order') }}</button>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-[#1a1a1a] border border-gray-800 p-6 sticky top-24">
                    <h2 class="text-xl font-serif mb-6">{{ __('Order Summary') }}</h2>
                    
                    <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex gap-3">
                                <div class="w-14 h-14 bg-gray-900 flex-shrink-0 relative">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-[#d4af37] text-black text-xs font-bold rounded-full flex items-center justify-center" x-text="item.quantity"></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm" x-text="item.name"></p>
                                    <p class="text-gray-500 text-sm" x-text="'$' + item.price"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="space-y-3 border-t border-gray-800 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">{{ __('Subtotal') }}</span>
                            <span x-text="'$' + $store.cart.subtotal"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">{{ __('Shipping') }}</span>
                            <span class="text-[#d4af37]">{{ __('Free') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-400">{{ __('Tax') }} (8%)</span>
                            <span x-text="'$' + $store.cart.tax"></span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-800">
                            <span class="font-medium">{{ __('Total') }}</span>
                            <span class="text-xl gold-gradient font-serif" x-text="'$' + $store.cart.total"></span>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <div class="flex items-start gap-2 text-gray-500 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#d4af37] mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <p>{{ __('Secure Checkout') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
