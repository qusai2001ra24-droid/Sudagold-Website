@extends('admin.layouts.app')

@section('title', 'Inventory Report')
@section('page_title', 'Inventory Stock Report')

@section('header_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.analytics.inventory') }}" class="flex items-center gap-2">
        <select name="status" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm" onchange="this.form.submit()">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Products</option>
            <option value="in_stock" {{ $status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
            <option value="low_stock" {{ $status == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="out_of_stock" {{ $status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>
    </form>
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#c9a227] transition-colors text-sm font-medium">
            Export
        </button>
        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg shadow-xl z-50">
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'inventory']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'inventory']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
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
            <span class="text-gray-400 text-sm">Total Products</span>
            <p class="text-2xl font-bold text-white mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">In Stock</span>
            <p class="text-2xl font-bold text-green-400 mt-2">{{ $stats['inStock'] }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Low Stock</span>
            <p class="text-2xl font-bold text-yellow-400 mt-2">{{ $stats['lowStock'] }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Out of Stock</span>
            <p class="text-2xl font-bold text-red-400 mt-2">{{ $stats['outOfStock'] }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Stock Distribution</h3>
        <canvas id="inventoryChart" height="100"></canvas>
    </div>

    <!-- Table -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#1f1f1f]">
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Product</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">SKU</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Category</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Stock</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Price</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->category?->name ?? 'Uncategorized' }}</td>
                        <td class="px-6 py-4 text-white font-medium">{{ $product->stock_quantity }}</td>
                        <td class="px-6 py-4 text-gray-300">${{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($product->stock_quantity <= 0)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Out of Stock</span>
                            @elseif($product->is_low_stock)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Low Stock</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">In Stock</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    new Chart(inventoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [{{ $stats['inStock'] }}, {{ $stats['lowStock'] }}, {{ $stats['outOfStock'] }}],
                backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
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
