@extends('admin.layouts.app')

@section('title', 'Monthly Revenue')
@section('page_title', 'Monthly Revenue Report')

@section('header_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.analytics.monthly-revenue') }}" class="flex items-center gap-2">
        <select name="year" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm">
            @for($y = now()->year; $y >= now()->year - 5; $y--)
            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
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
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'monthly-revenue']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'monthly-revenue']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
                Export Excel
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Revenue ({{ $year }})</span>
            <p class="text-2xl font-bold text-white mt-2">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Orders ({{ $year }})</span>
            <p class="text-2xl font-bold text-white mt-2">{{ $totalOrders }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Monthly Revenue - {{ $year }}</h3>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    <!-- Table -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#1f1f1f]">
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Month</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Revenue</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Orders</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Avg Order Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $data)
                    <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $data['month'] }}</td>
                        <td class="px-6 py-4 text-white">${{ number_format($data['revenue'], 2) }}</td></td>
                        <td class="px-6 py-4 text-gray-300">{{ $data['orders'] }}</td>
                        <td class="px-6 py-4 text-gray-300">${{ number_format($data['orders'] > 0 ? $data['revenue'] / $data['orders'] : 0, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-[#1a1a1a]">
                        <td class="px-6 py-4 text-white font-bold">Total</td>
                        <td class="px-6 py-4 text-[#d4af37] font-bold">${{ number_format($totalRevenue, 2) }}</td>
                        <td class="px-6 py-4 text-white font-bold">{{ $totalOrders }}</td>
                        <td class="px-6 py-4 text-gray-300">${{ number_format($totalOrders > 0 ? $totalRevenue / $totalOrders : 0, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyData->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlyData->pluck('revenue')) !!},
                backgroundColor: '#d4af37',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: '#1f1f1f' }, ticks: { color: '#9ca3af' } },
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
