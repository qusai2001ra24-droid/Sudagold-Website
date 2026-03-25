@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page_title', 'Users')

@section('header_actions')
<a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
    Add User
</a>
@endsection

@section('content')
<div class="bg-[#111111] border border-[#1f1f1f] rounded-xl">
    <!-- Filters -->
    <div class="p-6 border-b border-[#1f1f1f]">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search users..."
                    class="w-full px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-[#d4af37]">
            </div>
            <select name="role"
                class="px-4 py-2 bg-[#0a0a0a] border border-[#1f1f1f] rounded-lg text-white focus:outline-none focus:border-[#d4af37]">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $roleId == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-[#d4af37] text-black rounded-lg hover:bg-[#e8c547] transition-colors text-sm font-medium">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#0a0a0a]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Orders</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1f1f1f]">
                @forelse($users as $user)
                <tr class="hover:bg-[#151515] transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#d4af37]/10 flex items-center justify-center">
                                <span class="text-[#d4af37] font-serif">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $user->full_name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#d4af37]/10 text-[#d4af37]">
                            {{ $user->role?->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                        {{ $user->orders()->count() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                        {{ $user->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-[#d4af37] hover:text-[#e8c547] transition-colors">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                                    {{ $user->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-6 border-t border-[#1f1f1f]">
        {{ $users->links() }}
    </div>
</div>
@endsection
