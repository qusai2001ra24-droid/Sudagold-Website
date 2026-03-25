@extends('admin.layouts.app')

@section('title', 'Edit Gold Price')
@section('page_title', 'Edit Gold Price')

@section('content')
<form method="POST" action="{{ route('admin.gold-prices.update', $goldPrice->id) }}" class="space-y-6 max-w-2xl">
    @csrf
    @method('PUT')
    <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
        <div class="space-y-4">
            <div>
                <label class="text-gray-400 text-sm mb-2 block">Gold Purity *</label>
                <select name="purity" required
                    class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                    <option value="24k" {{ $goldPrice->purity == '24k' ? 'selected' : '' }}>24k</option>
                    <option value="22k" {{ $goldPrice->purity == '22k' ? 'selected' : '' }}>22k</option>
                    <option value="18k" {{ $goldPrice->purity == '18k' ? 'selected' : '' }}>18k</option>
                    <option value="14k" {{ $goldPrice->purity == '14k' ? 'selected' : '' }}>14k</option>
                    <option value="10k" {{ $goldPrice->purity == '10k' ? 'selected' : '' }}>10k</option>
                </select>
                @error('purity') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-gray-400 text-sm mb-2 block">Price per Gram ($) *</label>
                <input type="number" step="0.01" name="price_per_gram" value="{{ old('price_per_gram', $goldPrice->price_per_gram) }}" required
                    class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                @error('price_per_gram') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-gray-400 text-sm mb-2 block">Price per Ounce ($)</label>
                <input type="number" step="0.01" name="price_per_ounce" value="{{ old('price_per_ounce', $goldPrice->price_per_ounce) }}"
                    class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
            </div>
            <div>
                <label class="text-gray-400 text-sm mb-2 block">Effective Date *</label>
                <input type="date" name="effective_from" value="{{ old('effective_from', $goldPrice->effective_from->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                @error('effective_date') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="set_current" value="1"
                        class="w-4 h-4 bg-[#0a0a0a] border-[#1f1f1f] rounded focus:ring-[#d4af37]">
                    <span class="text-gray-400 text-sm">Set as current price for this purity</span>
                </label>
            </div>
            <div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" {{ $goldPrice->is_active ? 'checked' : '' }}
                        class="w-4 h-4 bg-[#0a0a0a] border-[#1f1f1f] rounded focus:ring-[#d4af37]">
                    <span class="text-gray-400 text-sm">Active</span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex gap-4">
        <button type="submit" class="px-6 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
            Update Price
        </button>
        <a href="{{ route('admin.gold-prices.index') }}" class="px-6 py-2 border border-[#1f1f1f] text-gray-400 rounded-lg hover:bg-[#1a1a1a] transition-colors text-sm">
            Cancel
        </a>
    </div>
</form>
@endsection
