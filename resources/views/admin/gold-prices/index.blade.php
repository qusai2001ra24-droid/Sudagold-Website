@extends('admin.layouts.app')

@section('title', 'Gold Price Management')
@section('page_title', 'Gold Prices')

@section('header_actions')
<a href="{{ route('admin.gold-prices.create') }}" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
    Add Price
</a>
@endsection

@section('content')
<div class="bg-[#111111] border border-[#1f1f1f] rounded-xl">
    <!-- Current Prices -->
    <div class="p-6 border-b border-[#1f1f1f]">
        <h3 class="text-lg font-semibold text-white mb-4">Current Gold Prices</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach(['24k', '22k', '18k', '14k', '10k'] as $purity)
            @php $price = \App\Models\GoldPrice::current()->where('purity', $purity)->first(); @endphp
            <div class="bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg p-4 text-center">
                <p class="text-gray-400 text-sm">{{ $purity }}</p>
                <p class="text-xl font-semibold text-[#d4af37]">${{ $price ? number_format($price->price_per_gram, 2) : 'N/A' }}</p>
                <p class="text-xs text-gray-500">per gram</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Price History -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#0a0a0a]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Purity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Price/Gram</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Price/Ounce</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Effective Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1f1f1f]">
                @forelse($goldPrices as $price)
                <tr class="hover:bg-[#151515] transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $price->purity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-[#d4af37]">${{ number_format($price->price_per_gram, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">${{ number_format($price->price_per_ounce ?? 0, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">{{ $price->effective_from->format('M j, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $price->is_active ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $price->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.gold-prices.edit', $price->id) }}" class="text-[#d4af37] hover:text-[#e8c547] transition-colors">Edit</a>
                            @if(!$price->is_active)
                            <a href="{{ route('admin.gold-prices.setCurrent', $price->id) }}" class="text-gray-400 hover:text-white transition-colors">Set Active</a>
                            @endif
                            <form method="POST" action="{{ route('admin.gold-prices.destroy', $price->id) }}" class="inline" onsubmit="return confirm('Delete this price?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No gold prices found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-6 border-t border-[#1f1f1f]">
        {{ $goldPrices->links() }}
    </div>
</div>
@endsection
