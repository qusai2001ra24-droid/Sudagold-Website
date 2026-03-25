@extends('admin.layouts.app')

@section('title', 'Reports Center')
@section('page_title', 'Reports')

@section('header_actions')
<a href="{{ route('admin.reports.export') }}" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
    Export
</a>
@endsection

@section('content')
<!-- Period Filter -->
<div class="mb-6">
    <form method="GET" class="flex gap-4">
        <select name="period" onchange="this.form.submit()"
            class="px-4 py-2 bg-[#111111] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
            <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>This Quarter</option>
            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
        </select>
    </form>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <p class="text-gray-500 text-sm">Total Revenue</p>
        <p class="text-2xl font-semibold text-white mt-1">${{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <p class="text-gray-500 text-sm">Total Orders</p>
        <p class="text-2xl font-semibold text-white mt-1">{{ $totalOrders }}</p>
    </div>
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <p class="text-gray-500 text-sm">Total Customers</p>
        <p class="text-2xl font-semibold text-white mt-1">{{ $totalCustomers }}</p>
    </div>
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <p class="text-gray-500 text-sm">New Customers</p>
        <p class="text-2xl font-semibold text-white mt-1">{{ $newCustomers }}</p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Daily Sales</h3>
        <canvas id="dailySalesChart" height="250"></canvas>
    </div>
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Monthly Revenue</h3>
        <canvas id="monthlyRevenueChart" height="250"></canvas>
    </div>
</div>

<!-- Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Best Selling -->
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Best Selling Products</h3>
        <div class="space-y-4">
            @forelse($bestSellingProducts as $item)
            <div class="flex items-center justify-between py-3 border-b border-[#1f1f1f] last:border-0">
                <div class="flex items-center gap-3">
                    <span class="text-[#d4af37] font-semibold">{{ $loop->iteration }}</span>
                    <div>
                        <p class="font-medium text-white">{{ $item->product?->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $item->product?->sku }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-medium text-white">{{ $item->total_sold }} sold</p>
                   _sold }} sold <p class="text-sm text-[#d4af37]">${{ number_format($item->total_revenue, 2) }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No data available</p>
            @endforelse
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Orders by Status</h3>
        <canvas id="statusChart" height="250"></canvas>
    </div>
</div>

@push('scripts')
<script>
    const dailyData = @json($dailySales);
    const dailyLabels = dailyData.map(d => d.date);
    const dailyValues = dailyData.map(d => d.total);
    const dailyCounts = dailyData.map(d => d.count);

    new Chart(document.getElementById('dailySalesChart'), {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Revenue',
                data: dailyValues,
                backgroundColor: '#d4af37',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af', callback: v => '$' + v.toLocaleString() } }
            }
        }
    });

    const monthlyData = @json($monthlyRevenue);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const monthlyValues = Array(12).fill(0);
    monthlyData.forEach(m => { monthlyValues[m.month - 1] = m.total; });

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue',
                data: monthlyValues,
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af', callback: v => '$' + v.toLocaleString() } }
            }
        }
    });

    const statusData = @json($ordersByStatus);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#fbbf24', '#3b82f6', '#a855f7', '#22c55e', '#ef4444'],
                borderColor: '#111111',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af' } } }
        }
    });
</script>
@endpush
@endsection
