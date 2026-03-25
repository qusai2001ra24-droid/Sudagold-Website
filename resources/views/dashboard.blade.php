@extends('layouts.frontend')

@section('title', 'لوحة التحكم')

@section('content')
<!-- Page Header -->
<section class="pt-32 pb-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">أهلا بعودتك</p>
                <h1 class="text-4xl md:text-5xl font-serif mb-2">لوحة التحكم</h1>
                <p class="text-gray-500">مرحباً {{ auth()->user()->first_name }}!</p>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 rounded-full bg-gold/10 flex items-center justify-center">
                    <span class="text-3xl font-serif text-gold">{{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? '', 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dashboard Content -->
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 sticky top-24">
                    <div class="text-center mb-6 pb-6 border-b border-gray-800">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gold/10 flex items-center justify-center">
                            <span class="text-2xl font-serif text-gold">{{ substr(auth()->user()->first_name ?? 'U', 0, 1) }}{{ substr(auth()->user()->last_name ?? '', 0, 1) }}</span>
                        </div>
                        <h3 class="font-serif text-lg text-white">{{ auth()->user()->full_name }}</h3>
                        <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <nav class="space-y-2">
                        <a href="#overview" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg bg-gold/10 text-gold" data-section="overview">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            نظرة عامة
                        </a>
                        <a href="#orders" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-colors" data-section="orders">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            الطلبات
                        </a>
                        <a href="#profile" class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition-colors" data-section="profile">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            الملف الشخصي
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition-colors w-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                تسجيل الخروج
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Overview Section -->
                <div id="overview-section">
                    <!-- Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-gray-400 text-sm">إجمالي الطلبات</span>
                                <div class="w-10 h-10 bg-gold/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-white">{{ $totalOrders }}</p>
                        </div>
                        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-gray-400 text-sm">إجمالي الإنفاق</span>
                                <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-white">${{ number_format($totalSpent, 2) }}</p>
                        </div>
                        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-gray-400 text-sm">طلبات قيد الانتظار</span>
                                <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-2xl font-bold text-white">{{ $pendingOrders }}</p>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                        <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                            <h3 class="text-lg font-serif text-white">الطلبات الأخيرة</h3>
                            <a href="#orders" class="text-gold hover:text-gold-light text-sm" onclick="showSection('orders')">عرض الكل</a>
                        </div>
                        @if($orders->isEmpty())
                        <div class="p-6 text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p>لا توجد طلبات بعد</p>
                            <a href="{{ route('catalog') }}" class="text-gold hover:underline mt-2 inline-block">بدء التسوق</a>
                        </div>
                        @else
                        <div class="divide-y divide-gray-800">
                            @foreach($orders->take(3) as $order)
                            <div class="p-4 hover:bg-gray-800/50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-white font-medium">{{ $order->order_number }}</p>
                                        <p class="text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d') }}</p>
                                    </div>
                                    <div class="text-left">
                                        <p class="gold-text font-medium">${{ number_format($order->total_price, 2) }}</p>
                                        <span class="inline-block px-2 py-1 text-xs rounded-full {{ $order->order_status === 'delivered' ? 'bg-green-500/20 text-green-400' : ($order->order_status === 'processing' ? 'bg-blue-500/20 text-blue-400' : 'bg-amber-500/20 text-amber-400') }}">
                                            @if($order->order_status === 'pending') قيد المراجعة
                                            @elseif($order->order_status === 'processing') قيد التجهيز
                                            @elseif($order->order_status === 'shipped') تم الشحن
                                            @elseif($order->order_status === 'delivered') تم التوصيل
                                            @elseif($order->order_status === 'cancelled') ملغي
                                            @else {{ $order->order_status }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Orders Section -->
                <div id="orders-section" class="hidden">
                    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                        <div class="p-6 border-b border-gray-800">
                            <h3 class="text-lg font-serif text-white">طلباتي</h3>
                        </div>
                        @if($orders->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="mb-4">لم تقم بأي طلبات بعد</p>
                            <a href="{{ route('catalog') }}" class="btn-gold inline-block">تصفح المنتجات</a>
                        </div>
                        @else
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-800/50">
                                    <tr>
                                        <th class="text-right px-6 py-3 text-gray-400 text-sm font-medium">رقم الطلب</th>
                                        <th class="text-right px-6 py-3 text-gray-400 text-sm font-medium">التاريخ</th>
                                        <th class="text-right px-6 py-3 text-gray-400 text-sm font-medium">الحالة</th>
                                        <th class="text-right px-6 py-3 text-gray-400 text-sm font-medium">الإجمالي</th>
                                        <th class="text-right px-6 py-3 text-gray-400 text-sm font-medium">التفاصيل</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    @foreach($orders as $order)
                                    <tr class="hover:bg-gray-800/50 transition-colors">
                                        <td class="px-6 py-4 text-white font-medium">{{ $order->order_number }}</td>
                                        <td class="px-6 py-4 text-gray-400">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2 py-1 text-xs rounded-full {{ $order->order_status === 'delivered' ? 'bg-green-500/20 text-green-400' : ($order->order_status === 'processing' ? 'bg-blue-500/20 text-blue-400' : ($order->order_status === 'shipped' ? 'bg-purple-500/20 text-purple-400' : 'bg-amber-500/20 text-amber-400')) }}">
                                                @if($order->order_status === 'pending') قيد المراجعة
                                                @elseif($order->order_status === 'processing') قيد التجهيز
                                                @elseif($order->order_status === 'shipped') تم الشحن
                                                @elseif($order->order_status === 'delivered') تم التوصيل
                                                @elseif($order->order_status === 'cancelled') ملغي
                                                @else {{ $order->order_status }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 gold-text font-medium">${{ number_format($order->total_price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <button type="button" onclick="showOrderDetails({{ $order->id }})" class="text-gold hover:text-gold-light text-sm">عرض</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Section -->
                <div id="profile-section" class="hidden">
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                        <h3 class="text-lg font-serif text-white mb-6">معلومات الملف الشخصي</h3>
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('patch')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-gray-400 text-sm mb-2 block">الاسم الأول</label>
                                    <input type="text" name="first_name" value="{{ auth()->user()->first_name }}" class="input-luxury w-full">
                                </div>
                                <div>
                                    <label class="text-gray-400 text-sm mb-2 block">اسم العائلة</label>
                                    <input type="text" name="last_name" value="{{ auth()->user()->last_name }}" class="input-luxury w-full">
                                </div>
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" class="input-luxury w-full">
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm mb-2 block">رقم الهاتف</label>
                                <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" class="input-luxury w-full">
                            </div>
                            <button type="submit" class="btn-gold group relative overflow-hidden">
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                <span class="sparkle"></span>
                                حفظ التغييرات
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Order Details Modal -->
<div id="orderModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeOrderModal()"></div>
    <div class="absolute inset-4 md:inset-20 bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="flex flex-col h-full">
            <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                <h3 class="text-xl font-serif text-white" id="modalOrderNumber">تفاصيل الطلب</h3>
                <button onclick="closeOrderModal()" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6" id="modalContent">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showSection(section) {
    const sections = ['overview', 'orders', 'profile'];
    const navLinks = document.querySelectorAll('.nav-link');
    
    sections.forEach(s => {
        document.getElementById(s + '-section').classList.add('hidden');
    });
    navLinks.forEach(link => {
        if (link.dataset.section === section) {
            link.classList.remove('text-gray-400', 'hover:bg-gray-800', 'hover:text-white');
            link.classList.add('bg-gold/10', 'text-gold');
        } else {
            link.classList.add('text-gray-400', 'hover:bg-gray-800', 'hover:text-white');
            link.classList.remove('bg-gold/10', 'text-gold');
        }
    });
    document.getElementById(section + '-section').classList.remove('hidden');
}

const ordersData = @json($orders->keyBy('id'));

function showOrderDetails(orderId) {
    const order = ordersData[orderId];
    if (!order) return;
    
    document.getElementById('modalOrderNumber').textContent = 'طلب ' + order.order_number;
    
    const statusText = {
        'pending': 'قيد المراجعة',
        'processing': 'قيد التجهيز',
        'shipped': 'تم الشحن',
        'delivered': 'تم التوصيل',
        'cancelled': 'ملغي'
    };
    
    const statusClass = {
        'pending': 'bg-amber-500/20 text-amber-400',
        'processing': 'bg-blue-500/20 text-blue-400',
        'shipped': 'bg-purple-500/20 text-purple-400',
        'delivered': 'bg-green-500/20 text-green-400',
        'cancelled': 'bg-red-500/20 text-red-400'
    };
    
    let itemsHtml = '';
    order.items.forEach(item => {
        itemsHtml += `
            <div class="flex justify-between items-center py-3 border-b border-gray-800">
                <div>
                    <p class="text-white">${item.product_name}</p>
                    <p class="text-gray-500 text-sm">${item.gold_purity} • ${item.weight}g × ${item.quantity}</p>
                </div>
                <p class="gold-text">$${parseFloat(item.subtotal).toFixed(2)}</p>
            </div>
        `;
    });
    
    document.getElementById('modalContent').innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h4 class="text-white font-medium mb-4">معلومات الطلب</h4>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-400">رقم الطلب: <span class="text-white">${order.order_number}</span></p>
                    <p class="text-gray-400">التاريخ: <span class="text-white">${new Date(order.created_at).toLocaleDateString('ar-SA')}</span></p>
                    <p class="text-gray-400">الحالة: <span class="inline-block px-2 py-0.5 text-xs rounded-full ${statusClass[order.order_status]}">${statusText[order.order_status]}</span></p>
                    <p class="text-gray-400">طريقة الدفع: <span class="text-white">${order.payment_method === 'cash_on_delivery' ? 'الدفع عند الاستلام' : order.payment_method}</span></p>
                </div>
                
                <h4 class="text-white font-medium mb-4 mt-8">عنوان الشحن</h4>
                <div class="text-sm text-gray-400">
                    <p>${order.shipping_first_name} ${order.shipping_last_name}</p>
                    <p>${order.shipping_address}</p>
                    <p>${order.shipping_city}, ${order.shipping_state} ${order.shipping_zip_code}</p>
                    <p>${order.shipping_country}</p>
                    <p class="mt-2">${order.shipping_phone}</p>
                </div>
            </div>
            <div>
                <h4 class="text-white font-medium mb-4">المنتجات</h4>
                ${itemsHtml}
                <div class="mt-6 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">المجموع الفرعي</span>
                        <span class="text-white">$${parseFloat(order.subtotal).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">الشحن</span>
                        <span class="text-green-400">مجاني</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">الضريبة</span>
                        <span class="text-white">$${parseFloat(order.tax_amount).toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-700 pt-2 font-medium">
                        <span class="text-white">الإجمالي</span>
                        <span class="gold-text">$${parseFloat(order.total_price).toFixed(2)}</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('orderModal').classList.remove('hidden');
}

function closeOrderModal() {
    document.getElementById('orderModal').classList.add('hidden');
}

// Handle hash in URL
if (window.location.hash) {
    const section = window.location.hash.substring(1);
    showSection(section);
}
</script>
@endpush
