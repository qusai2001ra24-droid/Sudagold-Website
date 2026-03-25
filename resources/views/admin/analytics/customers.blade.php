@extends('admin.layouts.app')

@section('title', 'Customer Analytics')
@section('page_title', 'Customer Purchase Analytics')

@section('header_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.analytics.customers') }}" class="flex items-center gap-2">
        <select name="period" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm" onchange="this.form.submit()">
            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
        </select>
    </form>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Export
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg shadow-xl z-50">
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'customers']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'customers']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
                Export Excel
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Customers</span>
            <p class="text-2xl font-bold text-white mt-2">{{ $stats['totalCustomers'] }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Active Customers</span>
            <p class="text-2xl font-bold text-green-400 mt-2">{{ $stats['activeCustomers'] }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Revenue</span>
            <p class="text-2xl font-bold text-white mt-2">${{ number_format($stats['totalRevenue'], 2) }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Avg Order Value</span>
            <p class="text-2xl font-bold text-white mt-2">${{ number_format($stats['averageOrderValue'], 2) }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Top Customers by Spending</h3>
        <canvas id="customersChart" height="100"></canvas>
    </div>

    <!-- Table -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#1f1f1f]">
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Customer</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Email</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Total Orders</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Total Spent</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Avg Order</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Last Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $customer->full_name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $customer->email }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $customer->total_orders ?? 0 }}</td>
                        <td class="px-6 py-4 text-[#d4af37] font-semibold">${{ number_format($customer->total_spent ?? 0, 2) }}</td>
                        <td class="px-6 py-4 text-gray-300">${{ number_format(($customer->total_orders ?? 0) > 0 ? ($customer->total_spent ?? 0) / $customer->total_orders : 0, 2) }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $customer->last_order_date ? \Carbon\Carbon::parse($customer->last_order_date)->format('M d, Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const customersCtx = document.getElementById('customersChart').getContext('2d');
    const topCustomers = {!! json_encode($customers->take(10)) !!};
    new Chart(customersCtx, {
        type: 'bar',
        data: {
            labels: topCustomers.map(c => c.first_name + ' ' + c.last_name),
            datasets: [{
                label: 'Total Spent',
                data: topCustomers.map(c => c.total_spent || 0),
                backgroundColor: '#d4af37',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#9ca3af' } },
                y: { 
                    grid: { color: '#1f1f1f' }, 
                    ticks: { 
                        color: '#9ca3af',
                        callback: function(value) { return '$' + value.toLocaleString(); }
                    } 
                }
            }
        }
    });
</script>
@endpush
@endsection
