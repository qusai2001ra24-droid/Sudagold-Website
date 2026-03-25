@extends('admin.layouts.app')

@section('title', 'Orders Management')
@section('page_title', 'Orders')

@section('header_actions')
<a href="{{ route('admin.orders.create') ?? '#' }}" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
    Export
</a>
@endsection

@section('content')
<div class="bg-[#111111] border border-[#1f1f1f] rounded-xl">
    <!-- Filters -->
    <div class="p-6 border-b border-[#1f1f1f]">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search orders..."
                    class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-[#d4af37]">
            </div>
            <select name="status"
                class="px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                <option value="">All Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#0a0a0a]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1f1f1f]">
                @forelse($orders as $order)
                <tr class="hover:bg-[#151515] transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-medium text-white">{{ $order->order_number }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-white">{{ $order->user?->full_name ?? 'Guest' }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user?->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-[#d4af37] font-medium">${{ number_format($order->total_price, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($order->order_status === 'delivered') bg-green-500/20 text-green-400
                            @elseif($order->order_status === 'processing') bg-blue-500/20 text-blue-400
                            @elseif($order->order_status === 'shipped') bg-purple-500/20 text-purple-400
                            @elseif($order->order_status === 'cancelled') bg-red-500/20 text-red-400
                            @else bg-yellow-500/20 text-yellow-400 @endif">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                        {{ $order->ordered_at?->format('M j, Y g:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#d4af37] hover:text-[#e8c547] transition-colors">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-[#1f1f1f]">
        {{ $orders->links() }}
    </div>
</div>
@endsection
