<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'سوداقولد') - سوداقولد</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white antialiased" dir="rtl">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/90 backdrop-blur-md border-b border-gray-800" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <x-luxury-logo />

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="link-luxury group relative {{ request()->routeIs('home') ? 'text-[#d4af37]' : '' }}">
                        <span class="relative z-10">الصفحة الرئيسية</span>
                    </a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="link-luxury flex items-center gap-1">
                            <span class="relative z-10">التسوق</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute top-full left-0 mt-2 w-48 bg-[#0f0f0f] border border-[#1f1f1f] rounded-lg shadow-xl z-50">
                            <a href="{{ route('catalog') }}" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37] rounded-t-lg">جميع المنتجات</a>
                            <a href="{{ route('catalog') }}?category=rings" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37]">الخواتم</a>
                            <a href="{{ route('catalog') }}?category=necklaces" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37]">القلائد</a>
                            <a href="{{ route('catalog') }}?category=bracelets" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37]">الأساور</a>
                            <a href="{{ route('catalog') }}?category=earrings" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37] rounded-b-lg">حلقات الأذن</a>
                        </div>
                    </div>
                    <a href="{{ route('catalog') }}" class="link-luxury {{ request()->routeIs('catalog') ? 'text-[#d4af37]' : '' }}">
                        <span class="relative z-10">المجموعات الذهبية</span>
                    </a>
                    <a href="{{ route('about') }}" class="link-luxury {{ request()->routeIs('about') ? 'text-[#d4af37]' : '' }}">
                        <span class="relative z-10">عن سوداقولد</span>
                    </a>
                    <a href="{{ route('gold-analytics') }}" class="link-luxury {{ request()->routeIs('gold-analytics') ? 'text-[#d4af37]' : '' }}">
                        <span class="relative z-10">تحليلات الذهب</span>
                    </a>
                    <a href="{{ route('contact') }}" class="link-luxury {{ request()->routeIs('contact') ? 'text-[#d4af37]' : '' }}">
                        <span class="relative z-10">اتصل بنا</span>
                    </a>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    
                    <!-- Cart -->
                    <button @click="$store.cart.open()" class="relative p-2 text-gray-300 hover:text-[#d4af37] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span x-show="$store.cart.itemCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-[#d4af37] text-black text-xs font-bold rounded-full flex items-center justify-center" x-text="$store.cart.itemCount"></span>
                    </button>

                    @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-gray-300 hover:text-[#d4af37] transition-colors">
                            <span>حسابي</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute top-full right-0 mt-2 w-48 bg-[#0f0f0f] border border-[#1f1f1f] rounded-lg shadow-xl z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37] rounded-t-lg">لوحة التحكم</a>
                            <a href="{{ route('dashboard') }}#orders" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37]">طلباتي</a>
                            <a href="{{ route('dashboard') }}#profile" class="block px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-[#d4af37]">ملفي الشخصي</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-300 hover:bg-[#1a1a1a] hover:text-red-400 rounded-b-lg">تسجيل الخروج</button>
                            </form>
                        </div>
                    </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:block px-4 py-2 text-gray-300 hover:text-[#d4af37] transition-colors link-luxury">
                            <span class="relative z-10">تسجيل الدخول</span>
                        </a>
                        <a href="{{ route('register') }}" class="hidden md:block px-4 py-2 bg-[#d4af37] text-black font-medium rounded-lg hover:bg-[#e8c547] transition-colors">
                            التسجيل
                        </a>
                    @endauth

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-black border-t border-gray-800">
            <div class="px-6 py-4 space-y-3">
                <a href="{{ route('home') }}" class="block text-gray-300 hover:text-[#d4af37]">الصفحة الرئيسية</a>
                <div class="space-y-1">
                    <button @click="mobileSubmenu = !mobileSubmenu" class="flex items-center justify-between w-full text-gray-300 hover:text-[#d4af37]">
                        <span>التسوق</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="mobileSubmenu" class="pl-4 space-y-2">
                        <a href="{{ route('catalog') }}" class="block text-gray-400 hover:text-[#d4af37]">جميع المنتجات</a>
                        <a href="{{ route('catalog') }}?category=rings" class="block text-gray-400 hover:text-[#d4af37]">الخواتم</a>
                        <a href="{{ route('catalog') }}?category=necklaces" class="block text-gray-400 hover:text-[#d4af37]">القلائد</a>
                        <a href="{{ route('catalog') }}?category=bracelets" class="block text-gray-400 hover:text-[#d4af37]">الأساور</a>
                        <a href="{{ route('catalog') }}?category=earrings" class="block text-gray-400 hover:text-[#d4af37]">حلقات الأذن</a>
                    </div>
                </div>
                <a href="{{ route('catalog') }}" class="block text-gray-300 hover:text-[#d4af37]">المجموعات الذهبية</a>
                <a href="{{ route('about') }}" class="block text-gray-300 hover:text-[#d4af37]">عن سوداقولد</a>
                <a href="{{ route('gold-analytics') }}" class="block text-gray-300 hover:text-[#d4af37]">تحليلات الذهب</a>
                <a href="{{ route('contact') }}" class="block text-gray-300 hover:text-[#d4af37]">اتصل بنا</a>
                <div class="pt-4 border-t border-gray-800">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-gray-300 hover:text-[#d4af37]">حسابي</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block text-gray-300 hover:text-red-400 mt-2">تسجيل الخروج</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-gray-300 hover:text-[#d4af37]">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="block text-[#d4af37] mt-2">التسجيل</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Cart Sidebar -->
    <div x-show="$store.cart.isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="$store.cart.close()"></div>
        <div class="absolute inset-y-0 right-0 w-full max-w-md bg-[#0a0a0a] border-l border-gray-800 shadow-xl">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between p-6 border-b border-gray-800">
                    <h2 class="text-xl font-serif">سلة التسوق</h2>
                    <button @click="$store.cart.close()" class="text-gray-400 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto p-6">
                    <template x-if="$store.cart.items.length === 0">
                        <div class="text-center py-12">
                            <p class="text-gray-500">سلتك فارغة</p>
                            <a href="{{ route('catalog') }}" class="text-[#d4af37] hover:underline mt-2 inline-block">متابعة التسوق</a>
                        </div>
                    </template>
                    <template x-for="item in $store.cart.items" :key="item.id">
                        <div class="flex gap-4 py-4 border-b border-gray-800">
                            <div class="w-20 h-20 bg-gray-900 rounded-lg overflow-hidden">
                                <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium" x-text="item.name"></h3>
                                <p class="text-sm text-gray-500" x-text="item.gold_purity"></p>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center border border-gray-700">
                                        <button @click="$store.cart.updateQuantity(item.id, item.quantity - 1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-[#d4af37]">-</button>
                                        <span class="w-8 text-center text-sm" x-text="item.quantity"></span>
                                        <button @click="$store.cart.updateQuantity(item.id, item.quantity + 1)" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-[#d4af37]">+</button>
                                    </div>
                                    <span class="text-[#d4af37]" x-text="'$' + (item.price * item.quantity)"></span>
                                </div>
                            </div>
                            <button @click="$store.cart.remove(item.id)" class="text-gray-500 hover:text-red-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                <template x-if="$store.cart.items.length > 0">
                    <div class="p-6 border-t border-gray-800">
                        <div class="flex justify-between mb-4">
                            <span class="text-gray-400">المجموع الفرعي</span>
                            <span x-text="'$' + $store.cart.subtotal"></span>
                        </div>
                        <a href="{{ route('cart') }}" class="block w-full py-3 text-center border border-gray-700 text-gray-300 rounded-lg hover:border-[#d4af37] hover:text-[#d4af37] transition-colors mb-3">
                            عرض السلة
                        </a>
                        <a href="{{ route('checkout') }}" class="block w-full py-3 text-center bg-[#d4af37] text-black font-medium rounded-lg hover:bg-[#e8c547] transition-colors">
                            الدفع
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-black border-t border-gray-800 py-16" id="contact">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <x-luxury-logo-animated />
                    <p class="text-gray-500 mt-6">مجوهرات ذهبية فاخرة مصنوعة بدقة وشغف. كل قطعة تحكي قصة أناقة.</p>
                </div>
                <div>
                    <h4 class="font-serif text-lg mb-4 text-white">روابط سريعة</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="{{ route('home') }}" class="link-luxury">الصفحة الرئيسية</a></li>
                        <li><a href="{{ route('about') }}" class="link-luxury">عن سوداقولد</a></li>
                        <li><a href="{{ route('catalog') }}" class="link-luxury">جميع المنتجات</a></li>
                        <li><a href="{{ route('gold-analytics') }}" class="link-luxury">تحليلات الذهب</a></li>
                        <li><a href="{{ route('contact') }}" class="link-luxury">اتصل بنا</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-serif text-lg mb-4 text-white">خدمات العملاء</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li><a href="#" class="link-luxury">الشحن</a></li>
                        <li><a href="#" class="link-luxury">الإرجاع</a></li>
                        <li><a href="#" class="link-luxury">الأسئلة الشائعة</a></li>
                        <li><a href="{{ route('contact') }}" class="link-luxury">اتصل بنا</a></li>
                        <li><a href="#" class="link-luxury">سياسة الخصوصية</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-serif text-lg mb-4 text-white">اتصل بنا</h4>
                    <ul class="space-y-2 text-gray-500">
                        <li>contact@sudagold.com</li>
                        <li>+249 995743687</li>
                        <li class="pt-2">
                            <div class="flex gap-4">
                                <a href="#" class="text-gray-500 hover:text-[#d4af37]">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                </a>
                                <a href="#" class="text-gray-500 hover:text-[#d4af37]">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                                <a href="#" class="text-gray-500 hover:text-[#d4af37]">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.85.38-1.78.64-2.75.76 1-.6 1.76-1.55 2.12-2.68-.93.55-1.96.95-3.06 1.17-.88-.94-2.13-1.53-3.51-1.53-2.66 0-4.81 2.16-4.81 4.81 0 .38.04.75.13 1.1-4-.2-7.58-2.11-9.96-5.02-.42.72-.66 1.56-.66 2.46 0 1.68.85 3.16 2.14 4.02-.79-.02-1.53-.24-2.18-.6v.06c0 2.35 1.67 4.31 3.88 4.76-.4.1-.83.16-1.27.16-.31 0-.62-.03-.92-.08.63 1.96 2.45 3.39 4.61 3.43-1.69 1.32-3.83 2.1-6.15 2.1-.4 0-.8-.02-1.19-.07 2.19 1.4 4.78 2.22 7.57 2.22 9.07 0 14.02-7.52 14.02-14.02 0-.21 0-.43-.01-.64.96-.69 1.79-1.56 2.45-2.55z"/></svg>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} سوداقولد. كل الحقوق محفوظة. 2026</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
