@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
@php
$totalRevenue = \App\Models\Order::where('order_status', '!=', 'cancelled')->sum('total_price');
$totalOrders = \App\Models\Order::count();
$todaySales = \App\Models\Order::where('order_status', '!=', 'cancelled')
    ->whereDate('ordered_at', today())
    ->sum('total_price');
$monthlyRevenue = \App\Models\Order::where('order_status', '!=', 'cancelled')
    ->whereMonth('ordered_at', now()->month)
    ->whereYear('ordered_at', now()->year)
    ->sum('total_price');
$totalCustomers = \App\Models\User::whereHas('orders')->count();

$dailySales = \App\Models\Order::where('order_status', '!=', 'cancelled')
    ->where('ordered_at', '>=', now()->subDays(30))
    ->selectRaw('DATE(ordered_at) as date, SUM(total_price) as total, COUNT(*) as count')
    ->groupBy('date')
    ->orderBy('date')
    ->get();

$bestSelling = \App\Models\OrderItem::whereHas('order', function ($q) {
        $q->where('order_status', '!=', 'cancelled');
    })
    ->selectRaw('product_id, SUM(quantity) as total_sold')
    ->groupBy('product_id')
    ->orderByDesc('total_sold')
    ->limit(5)
    ->get();

$ordersByStatus = \App\Models\Order::selectRaw('order_status, COUNT(*) as count')
    ->groupBy('order_status')
    ->pluck('count', 'order_status');
@endphp

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <p class="text-2xl font-semibold text-white mt-1">${{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-[#d4af37]/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Orders</p>
                <p class="text-2xl font-semibold text-white mt-1">{{ $totalOrders }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Daily Sales</p>
                <p class="text-2xl font-semibold text-white mt-1">${{ number_format($todaySales, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Monthly Revenue</p>
                <p class="text-2xl font-semibold text-white mt-1">${{ number_format($monthlyRevenue, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Customers</p>
                <p class="text-2xl font-semibold text-white mt-1">{{ $totalCustomers }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Pending Orders</p>
                <p class="text-2xl font-semibold text-white mt-1">{{ $ordersByStatus['pending'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37]/30 transition-colors">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Products</p>
                <p class="text-2xl font-semibold text-white mt-1">{{ \App\Models\Product::count() }}</p>
            </div>
            <div class="w-12 h-12 bg-pink-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Sales Chart -->
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Sales Trend (Last 30 Days)</h3>
        <canvas id="salesChart" height="250"></canvas>
    </div>

    <!-- Orders by Status -->
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Orders by Status</h3>
        <canvas id="ordersChart" height="250"></canvas>
    </div>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-[#d4af37] text-sm hover:text-[#e8c547]">View All</a>
        </div>
        <div class="space-y-4">
            @forelse(\App\Models\Order::latest()->limit(5)->get() as $order)
                <div class="flex items-center justify-between py-3 border-b border-[#1f1f1f] last:border-0">
                    <div>
                        <p class="font-medium text-white">{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user?->full_name ?? 'Guest' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-[#d4af37]">${{ number_format($order->total_price, 2) }}</p>
                        <span class="text-xs px-2 py-1 rounded-full 
                            @if($order->order_status === 'delivered') bg-green-500/20 text-green-400
                            @elseif($order->order_status === 'processing') bg-blue-500/20 text-blue-400
                            @elseif($order->order_status === 'shipped') bg-purple-500/20 text-purple-400
                            @else bg-yellow-500/20 text-yellow-400 @endif">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No orders yet</p>
            @endforelse
        </div>
    </div>

    <!-- Best Selling Products -->
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Best Selling Products</h3>
            <a href="{{ route('admin.products.index') }}" class="text-[#d4af37] text-sm hover:text-[#e8c547]">View All</a>
        </div>
        <div class="space-y-4">
            @forelse($bestSelling as $item)
                @if($item->product)
                <div class="flex items-center justify-between py-3 border-b border-[#1f1f1f] last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#1f1f1f] rounded-lg overflow-hidden">
                            @if($item->product->image)
                            <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->product->sku }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-white">{{ $item->total_sold }} sold</p>
                    </div>
                </div>
                @endif
            @empty
                <p class="text-gray-500 text-center py-4">No products sold yet</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    const salesData = @json($dailySales);
    const salesLabels = salesData.map(d => d.date);
    const salesValues = salesData.map(d => d.total);

    const salesChart = new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Revenue',
                data: salesValues,
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#d4af37',
                pointBorderColor: '#d4af37',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: '#1f1f1f' },
                    ticks: { color: '#9ca3af' }
                },
                y: {
                    grid: { color: '#1f1f1f' },
                    ticks: { 
                        color: '#9ca3af',
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    const ordersByStatus = @json($ordersByStatus);
    const ordersChart = new Chart(document.getElementById('ordersChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(ordersByStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: Object.values(ordersByStatus),
                backgroundColor: ['#fbbf24', '#3b82f6', '#a855f7', '#22c55e', '#ef4444'],
                borderColor: '#111111',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#9ca3af' }
                }
            }
        }
    });
</script>
@endpush
@endsection
