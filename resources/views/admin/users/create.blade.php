@extends('admin.layouts.app')

@section('title', 'Create User')
@section('page_title', 'Create User')

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Personal Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-gray-400 text-sm mb-2 block">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                        class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                    @error('first_name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-gray-400 text-sm mb-2 block">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                        class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                    @error('last_name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-gray-400 text-sm mb-2 block">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                    @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-[#111111] border border-[#1f1f1f] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Account Settings</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-gray-400 text-sm mb-2 block">Password *</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                    @error('password') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-gray-400 text-sm mb-2 block">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                </div>
                <div>
                    <label class="text-gray-400 text-sm mb-2 block">Role *</label>
                    <select name="role_id" required
                        class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 bg-[#0a0a0a] border-[#1f1f1f] rounded focus:ring-[#d4af37]">
                        <span class="text-gray-400 text-sm">Active</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-4">
        <button type="submit" class="px-6 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
            Create User
        </button>
        <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-[#1f1f1f] text-gray-400 rounded-lg hover:bg-[#1a1a1a] transition-colors text-sm">
            Cancel
        </a>
    </div>
</form>
@endsection
