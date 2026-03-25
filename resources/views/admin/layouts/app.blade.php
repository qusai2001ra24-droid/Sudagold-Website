<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Sudagold</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#0a0a0a] text-white">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#0f0f0f] border-r border-[#1f1f1f] flex flex-col fixed h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-[#1f1f1f]">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-2xl font-serif font-bold text-[#d4af37]">SUDA</span>
                    <span class="text-2xl font-serif font-light">GOLD</span>
                </a>
                <p class="text-xs text-gray-500 mt-1">Admin Panel</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                @canany(['view_products', 'create_products', 'edit_products', 'delete_products'])
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.products.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Products
                </a>
                @endcanany

                @canany(['view_categories', 'create_categories', 'edit_categories', 'delete_categories'])
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Categories
                </a>
                @endcanany

                @canany(['view_orders', 'edit_orders'])
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Orders
                </a>
                @endcanany

                @can('manage_users')
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Users
                </a>
                @endcan

                @can('manage_gold_prices')
                <a href="{{ route('admin.gold-prices.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.gold-prices.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Gold Prices
                </a>
                @endcan

                @can('view_reports')
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.reports.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Reports
                </a>
                @endcan

                <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all {{ request()->routeIs('admin.analytics.*') ? 'bg-[#d4af37]/10 text-[#d4af37]' : 'text-gray-400 hover:bg-[#1a1a1a] hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics
                </a>
            </nav>

            <!-- User Menu -->
            <div class="p-4 border-t border-[#1f1f1f]">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-[#d4af37]/10 flex items-center justify-center">
                        <span class="text-[#d4af37] font-serif">{{ substr(auth()->user()->first_name ?? 'A', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'U', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->full_name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->role?->name ?? 'Administrator' }}</p>
                    </div>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Site
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-400 hover:text-white transition-colors w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Header -->
            <header class="bg-[#0f0f0f] border-b border-[#1f1f1f] px-8 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-serif font-semibold text-white">@yield('page_title', 'Dashboard')</h1>
                    @yield('header_actions')
                </div>
            </header>

            <!-- Content -->
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-green-400">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <p class="text-red-400">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
