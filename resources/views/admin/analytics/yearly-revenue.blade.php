@extends('admin.layouts.app')

@section('title', 'Yearly Revenue')
@section('page_title', 'Yearly Revenue Report')

@section('header_actions')
<div class="flex items-center gap-3">
    <form method="GET" action="{{ route('admin.analytics.yearly-revenue') }}" class="flex items-center gap-2">
        <select name="start_year" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm">
            @for($y = now()->year; $y >= now()->year - 10; $y--)
            <option value="{{ $y }}" {{ $y == $startYear ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <span class="text-gray-400">to</span>
        <select name="end_year" class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-lg px-3 py-2 text-white text-sm">
            @for($y = now()->year; $y >= now()->year - 10; $y--)
            <option value="{{ $y }}" {{ $y == $endYear ? 'selected' : '' }}>{{ $y }}</option>
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
            <a href="{{ route('admin.analytics.export-pdf', ['type' => 'yearly-revenue']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-t-lg">
                Export PDF
            </a>
            <a href="{{ route('admin.analytics.export-excel', ['type' => 'yearly-revenue']) }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#252525] hover:text-white rounded-b-lg">
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
            <span class="text-gray-400 text-sm">Total Revenue</span>
            <p class="text-2xl font-bold text-white mt-2">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
            <span class="text-gray-400 text-sm">Total Orders</span>
            <p class="text-2xl font-bold text-white mt-2">{{ $totalOrders }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Yearly Revenue</h3>
        <canvas id="yearlyChart" height="100"></canvas>
    </div>

    <!-- Table -->
    <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#1f1f1f]">
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Year</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Revenue</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Orders</th>
                        <th class="text-left text-xs font-medium text-gray-400 uppercase tracking-wider px-6 py-4">Avg Order Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($yearlyData as $data)
                    <tr class="border-b border-[#1f1f1f] hover:bg-[#1a1a1a] transition-colors">
                        <td class="px-6 py-4 text-white font-medium">{{ $data['year'] }}</td>
                        <td class="px-6 py-4 text-white">${{ number_format($data['revenue'], 2) }}</td>
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

@php
    $chartLabels = $yearlyData->pluck('year')->toJson(JSON_UNESCAPED_UNICODE);
    $chartData = $yearlyData->pluck('revenue')->toJson();
@endphp

@push('scripts')
<script>
    const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
    new Chart(yearlyCtx, {
        type: 'line',
        data: {
            labels: {!! $chartLabels !!},
            datasets: [{
                label: 'Revenue',
                data: {!! $chartData !!},
                borderColor: '#d4af37',
                backgroundColor: 'rgba(212, 175, 55, 0.1)',
                fill: true,
                tension: 0.4
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
