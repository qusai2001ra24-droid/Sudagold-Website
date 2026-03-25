@extends('admin.layouts.app')

@section('title', 'Top Products')
@section('page_title', 'Top Selling Products')

@section('header_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.analytics.top-products') }}" class="flex items-center gap-2">
        <select name="period" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm">
            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>All Time</option>
            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>This Week</option>
            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>This Month</option>
            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>This Year</option>
        </select>
        <select name="limit" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm">
            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option>
            <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Filter
        </button>
    </form>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Export
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg shadow-xl z-50">
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'top-products']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'top-products']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
                Export Excel
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Chart -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Top {{ $limit }} Products by Revenue</h3>
        <canvas id="productsChart" height="200"></canvas>
    </div>

    <!-- Table -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#1f1f1f]">
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">#</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Product</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Units Sold</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition-colors">
                        <td class="px-6 py-4 text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-white font-medium">{{ $product->product_name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ number_format($product->total_sold) }}</td>
                        <td class="px-6 py-4 text-[#d4af37] font-semibold">${{ number_format($product->total_revenue, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($products->map(fn($n) => strlen($n->product_name) > 20 ? substr($n->product_name, 0, 20) . '...' : $n->product_name)) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($products->pluck('total_revenue')) !!},
                backgroundColor: '#d4af37',
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { 
                    grid: { color: '#1f1f1f' }, 
                    ticks: { 
                        color: '#9ca3af',
                        callback: function(value) { return '$' + value.toLocaleString(); }
                    } 
                },
                y: { grid: { display: false }, ticks: { color: '#9ca3af' } }
            }
        }
    });
</script>
@endpush
@endsection
