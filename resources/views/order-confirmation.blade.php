@extends('layouts.frontend')

@section('title', __('تأكيد الطلب'))

@section('content')
<!-- Success Banner -->
<section class="pt-32 pb-8 bg-black">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-500/20 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="text-gold uppercase tracking-wider text-sm mb-4">{{ __('Thank you for your order!') }}</p>
        <h1 class="text-4xl md:text-5xl font-serif mb-4">{{ __('Order Confirmation') }}</h1>
        <p class="text-gray-400">{{ __('Your order has been placed successfully.') }}</p>
    </div>
</section>

<!-- Order Details -->
<section class="py-16 bg-black">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Order Number Card -->
        <div class="bg-black-light border border-gray-800 rounded-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="text-gray-500 text-sm mb-1">{{ __('Order Number') }}</p>
                    <p class="text-2xl gold-gradient font-serif">{{ $order->order_number }}</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-gray-500 text-sm mb-1">{{ __('Order Date') }}</p>
                    <p class="text-white">{{ $order->ordered_at->format('F j, Y') }}</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-gray-500 text-sm mb-1">{{ __('Status') }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500/20 text-yellow-500">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-black-light border border-gray-800 rounded-xl p-6">
                    <h2 class="text-xl font-serif mb-6">{{ __('Items') }}</h2>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex gap-4 py-4 border-b border-gray-800 last:border-0">
                            <div class="w-20 h-20 bg-gray-900 flex-shrink-0 rounded-lg overflow-hidden">
                                @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium mb-1">{{ $item->product_name }}</h3>
                                <p class="text-gray-500 text-sm">SKU: {{ $item->sku }}</p>
                                <p class="text-gray-500 text-sm">{{ $item->gold_purity }} | {{ $item->weight }}g</p>
                            </div>
                            <div class="text-right">
                                <p class="gold-text font-medium">${{ number_format($item->price, 2) }}</p>
                                <p class="text-gray-500 text-sm">{{ __('الكمية') }}: {{ $item->quantity }}</p>
                                <p class="text-white font-medium">${{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-black-light border border-gray-800 rounded-xl p-6">
                    <h2 class="text-xl font-serif mb-4">{{ __('Shipping Information') }}</h2>
                    <div class="text-gray-400">
                        <p class="text-white">{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip_code }}</p>
                        <p>{{ $order->shipping_country }}</p>
                        <p class="mt-2">{{ $order->shipping_phone }}</p>
                        <p>{{ $order->shipping_email }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-black-light border border-gray-800 rounded-xl p-6 sticky top-24">
                    <h2 class="text-xl font-serif mb-6">{{ __('Order Summary') }}</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-400">
                            <span>{{ __('Subtotal') }}</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>{{ __('Shipping') }}</span>
                            <span class="text-gold">{{ __('Free') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>{{ __('Tax') }} (8%)</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-green-500">
                            <span>{{ __('الخصم') }}</span>
                            <span>-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="border-t border-gray-800 pt-4 flex justify-between">
                            <span class="font-medium">{{ __('Total') }}</span>
                            <span class="text-xl gold-gradient font-serif">${{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="pt-6 border-t border-gray-800">
                        <h3 class="font-medium mb-3">{{ __('Payment Method') }}</h3>
                        <div class="text-gray-400 text-sm space-y-2">
                            <p>
                                <span class="text-gray-500">{{ __('Payment Method') }}:</span> 
                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                            </p>
                            <p>
                                <span class="text-gray-500">{{ __('المرجع') }}:</span> 
                                {{ $order->transaction_reference }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <a href="{{ route('dashboard') }}" class="btn-outline-gold w-full text-center block">
                            {{ __('My Orders') }}
                        </a>
                        <a href="{{ route('catalog') }}" class="btn-gold w-full text-center block mt-4">
                            {{ __('Continue Shopping') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
