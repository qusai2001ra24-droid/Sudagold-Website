@extends('admin.layouts.app')

@section('title', 'Order Details')
@section('page_title', 'Order: ' . $order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Order Items</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 py-4 border-b border-[#1f1f1f] last:border-0">
                    <div class="w-16 h-16 bg-[#1f1f1f] rounded-lg overflow-hidden">
                        @if($item->product?->image)
                        <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-white">{{ $item->product_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $item->sku }} | {{ $item->gold_purity }} | {{ $item->weight }}g</p>
                    </div>
                    <div class="text-right">
                        <p class="text-white">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                        <p class="text-[#d4af37] font-medium">${{ number_format($item->subtotal, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Shipping Address</h3>
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

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Order Status -->
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Order Status</h3>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <select name="order_status" class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Order Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Shipping</span>
                    <span>${{ number_format($order->shipping_amount, 2) }}</span>
                <div class="flex</div>
                 justify-between text-gray-400">
                    <span>Discount</span>
                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                </div>
                <div class="border-t border-[#1f1f1f] pt-3 flex justify-between">
                    <span class="font-medium text-white">Total</span>
                    <span class="text-[#d4af37] font-semibold">${{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Payment</h3>
            <div class="space-y-2 text-gray-400">
                <p><span class="text-gray-500">Method:</span> {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</p>
                <p><span class="text-gray-500">Ref:</span> {{ $order->transaction_reference }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <a href="{{ route('admin.orders.index') }}" class="flex-1 px-4 py-2 border border-[#1f1f1f] text-gray-400 rounded-lg hover:bg-[#1a1a1a] transition-colors text-center text-sm">
                Back
            </a>
            <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" class="flex-1" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg hover:bg-red-500/20 transition-colors text-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
