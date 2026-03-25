@extends('admin.layouts.app')

@section('title', 'Create Category')
@section('page_title', 'Create Category')

@section('content')
<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
            @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
            <select name="parent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
                <option value="">None (Top Level)</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
            <input type="file" name="image" accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#d4af37] focus:border-[#d4af37]">
            @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-[#d4af37] focus:ring-[#d4af37]">
                <span class="ml-2 text-sm text-gray-700">Active</span>
            </label>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="px-6 py-2 bg-[#d4af37] text-black font-medium rounded-lg hover:bg-[#e8c547] transition-colors">
                Create Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection
