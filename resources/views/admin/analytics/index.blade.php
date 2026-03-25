@extends('admin.layouts.app')

@section('title', 'Analytics')
@section('page_title', 'Analytics Dashboard')

@section('header_actions')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.analytics.daily-sales') }}" class="px-4 py-2 bg-[#d4af37]/10 text-[#d4af37] rounded-lg hover:bg-[#d4af37]/20 transition-colors text-sm">
        Daily Sales
    </a>
    <a href="{{ route('admin.analytics.monthly-revenue') }}" class="px-4 py-2 bg-[#d4af37]/10 text-[#d4af37] rounded-lg hover:bg-[#d4af37]/20 transition-colors text-sm">
        Monthly
    </a>
    <a href="{{ route('admin.analytics.yearly-revenue') }}" class="px-4 py-2 bg-[#d4af37]/10 text-[#d4af37] rounded-lg hover:bg-[#d4af37]/20 transition-colors text-sm">
        Yearly
    </a>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Export
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg shadow-xl z-50">
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'dashboard']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'dashboard']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
                Export Excel
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 text-sm">Today's Sales</span>
                <div class="w-10 h-10 bg-[#d4af37]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">${{ number_format($stats['todaySales'], 2) }}</p>
        </div>

        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 text-sm">Today's Orders</span>
                <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['todayOrders'] }}</p>
        </div>

        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 text-sm">Monthly Revenue</span>
                <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">${{ number_format($stats['monthlyRevenue'], 2) }}</p>
        </div>

        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 text-sm">Yearly Revenue</span>
                <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">${{ number_format($stats['yearlyRevenue'], 2) }}</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 text-sm">Total Products</span>
                <div class="w-10 h-10 bg-orange-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['totalProducts'] }}</p>
        </div>

        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-400 text-sm">Total Customers</span>
                <div class="w-10 h-10 bg-pink-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">{{ $stats['totalCustomers'] }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Revenue Chart</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        <!-- Orders Chart -->
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Orders Chart</h3>
            <canvas id="ordersChart" height="200"></canvas>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Sales Chart (Last 7 Days)</h3>
        <canvas id="salesChart" height="100"></canvas>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.analytics.top-products') }}" class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37] transition-colors group">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-[#d4af37] transition-colors">Top Products</h3>
                    <p class="text-gray-400 text-sm mt-1">View best selling products</p>
                </div>
                <svg class="w-8 h-8 text-[#d4af37] opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.analytics.inventory') }}" class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37] transition-colors group">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-[#d4af37] transition-colors">Inventory Report</h3>
                    <p class="text-gray-400 text-sm mt-1">Stock levels and status</p>
                </div>
                <svg class="w-8 h-8 text-[#d4af37] opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.analytics.customers') }}" class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6 hover:border-[#d4af37] transition-colors group">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white group-hover:text-[#d4af37] transition-colors">Customer Analytics</h3>
                    <p class="text-gray-400 text-sm mt-1">Customer purchase behavior</p>
                </div>
                <svg class="w-8 h-8 text-[#d4af37] opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: {{ json_encode($revenueChart['labels']) }},
            datasets: [{
                label: 'Revenue',
                data: {{ json_encode($revenueChart['data']) }},
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
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

    // Orders Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: {{ json_encode($ordersChart['labels']) }},
            datasets: [{
                label: 'Orders',
                data: {{ json_encode($ordersChart['data']) }},
                backgroundColor: '#3b82f6',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
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
                    ticks: { color: '#9ca3af' }
                }
            }
        }
    });

    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {{ json_encode($salesChart['labels']) }},
            datasets: [{
                label: 'Sales',
                data: {{ json_encode($salesChart['data']) }},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
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
                    ticks: { color: '#9ca3af' }
                }
            }
        }
    });
</script>
@endpush
@endsection
