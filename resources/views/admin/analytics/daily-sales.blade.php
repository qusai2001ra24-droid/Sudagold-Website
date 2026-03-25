@extends('admin.layouts.app')

@section('title', 'Daily Sales')
@section('page_title', 'Daily Sales Report')

@section('header_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.analytics.daily-sales') }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm">
        <button type="submit" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Filter
        </button>
    </form>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Export
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg shadow-xl z-50">
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'daily-sales']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'daily-sales']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
                Export Excel
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Sales</span>
            <p class="text-2xl font-bold text-white mt-2">${{ number_format($totalSales, 2) }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Orders</span>
            <p class="text-2xl font-bold text-white mt-2">{{ $orderCount }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Average Order Value</span>
            <p class="text-2xl font-bold text-white mt-2">${{ number_format($orderCount > 0 ? $totalSales / $orderCount : 0, 2) }}</p>
        </div>
    </div>

    <!-- Hourly Chart -->
    @if($hourlyData->isNotEmpty())
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Hourly Breakdown</h3>
        <canvas id="hourlyChart" height="100"></canvas>
    </div>
    @endif

    <!-- Orders Table -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl overflow-hidden">
        <div class="p-6 border-b border-[#1f1f1f]">
            <h3 class="text-lg font-semibold text-white">Orders on {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#1f1f1f]">
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Order #</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Customer</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Items</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Total</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Status</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $order->user?->full_name ?? 'Guest' }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $order->items->sum('quantity') }}</td>
                        <td class="px-6 py-4 text-white">${{ number_format($order->total_price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->order_status === 'delivered') bg-green-500/20 text-green-400
                                @elseif($order->order_status === 'processing') bg-blue-500/20 text-blue-400
                                @elseif($order->order_status === 'pending') bg-yellow-500/20 text-yellow-400
                                @else bg-gray-500/20 text-gray-400 @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $order->ordered_at?->format('h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No orders found for this date</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
@if($hourlyData->isNotEmpty())
<script>
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourlyData->map(fn($h) => $h->hour . ':00')) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($hourlyData->map(fn($h) => $h->count)) !!},
                backgroundColor: '#d4af37',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af' } }
            }
        }
    });
</script>
@endif
@endpush
@endsection
