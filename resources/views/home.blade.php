@extends('layouts.frontend')

@section('title', 'الصفحة الرئيسية')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-black via-black to-gray-900"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-gold-dark rounded-full blur-3xl"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 text-center px-6 max-w-5xl mx-auto">
        <p class="text-gold uppercase font-bold tracking-[0.3em] text-sm mb-6 opacity-0 slide-up">مشروع تخرج من جامعة أمدرمان الإسلامية<br>أُسس. 2026م</p>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold mb-6 opacity-0 slide-up stagger-1">
            <span class="gold-gradient">أناقة</span>
            <span class="white">خالدة</span>
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 mb-4 font-light opacity-0 slide-up stagger-2">
            {{ 'إكتشف فن المجوهرات الذهبية العالية الجودة' }}
        </p>
        <p class="text-gray-500 mb-10 max-w-2xl mx-auto opacity-0 slide-up stagger-3">
            {{ 'كل قطعة هي عمل فني' }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center opacity-0 slide-up stagger-4">
            <a href="{{ route('catalog') }}" class="btn-gold group relative inline-flex items-center justify-center overflow-hidden">
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span>إكتشف المجموعات</span>
            </a>
            <a href="{{ route('about') }}" class="btn-outline-gold group relative inline-flex items-center justify-center overflow-hidden">
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span class="sparkle"></span>
                <span>إعرف المزيد</span>
            </a>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 opacity-0 fade-in" style="animation-delay: 1s;">
        <div class="w-6 h-10 border-2 border-gold/50 rounded-full flex justify-center pt-2">
            <div class="w-1 h-2 bg-gold rounded-full animate-bounce"></div>
        </div>
    </div>
</section>

<!-- Featured Collection -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">مختارة بعناية</p>
            <h2 class="text-4xl md:text-5xl font-serif mb-4">مجموعات مختارة</h2>
            <div class="w-24 h-px bg-gold mx-auto"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Collection Card 1 -->
            <a href="{{ route('catalog') }}?category=rings" class="group relative aspect-[3/4] overflow-hidden card-luxury">
                <img src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600&h=800&fit=crop" alt="خواتم ذهبية" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <h3 class="text-2xl font-serif mb-2">خواتم رائعة</h3>
                    <p class="text-gray-400 text-sm">رموز الحب الأبدية</p>
                </div>
            </a>
            
            <!-- Collection Card 2 -->
            <a href="{{ route('catalog') }}?category=necklaces" class="group relative aspect-[3/4] overflow-hidden card-luxury">
                <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&h=800&fit=crop" alt="قلائد ذهبية" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <h3 class="text-2xl font-serif mb-2">قلائد مميزة</h3>
                    <p class="text-gray-400 text-sm">أناقة رشيقة</p>
                </div>
            </a>
            
            <!-- Collection Card 3 -->
            <a href="{{ route('catalog') }}?category=bracelets" class="group relative aspect-[3/4] overflow-hidden card-luxury">
                <img src="https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=600&h=800&fit=crop" alt="أساور فاخرة" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8">
                    <h3 class="text-2xl font-serif mb-2">أساور فاخرة</h3>
                    <p class="text-gray-400 text-sm">أناقة المعصم</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-24 bg-black-light">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="aspect-square relative">
                    <img src="https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?w=800&h=800&fit=crop" alt="{{ __('Our Craft') }}" class="w-full h-full object-cover">
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 border border-gold/30"></div>
                    <div class="absolute -top-6 -left-6 w-32 h-32 border border-gold/20"></div>
                </div>
            </div>
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">حرفتنا</p>
                <h2 class="text-4xl md:text-5xl font-serif mb-6">جودة لا تنافس</h2>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    في سوداقولد، نؤمن أن المجوهرات الفاخرة ليست مجرد اكسسوار - بل هي تعبير عن الذوق الرفيع والقيمة الابدية. كل قطعة في مجموعتنا مصنوعة بعناية باستخدام اجود انواع الذهب عيار 18 و 24 قيراط، المصدرة بشكل أخلاقي والمصنوعة بدقة.
                </p>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    تساهم خبرتنا الممتدة لعقود في إضفاء لمسة إبداعية مميزة , مما يضمن أن كل قطعة تلبي معاييرنا الدقيقة للجودة , من التصميم الأولي إلي المنتج النهائي نلتزم إلتزاما راسخا بالجودة
                </p>
                <div class="grid grid-cols-3 gap-8 mb-8">
                    <div class="text-center">
                        <span class="text-3xl font-serif gold-gradient block">500+</span>
                        <span class="text-gray-500 text-sm">قطع فريدة</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-serif gold-gradient block">50+</span>
                        <span class="text-gray-500 text-sm">حرفيون ماهرون</span>
                    </div>
                    <div class="text-center">
                        <span class="text-3xl font-serif gold-gradient block">10k+</span>
                        <span class="text-gray-500 text-sm">عملاء سعداء</span>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="btn-outline-gold">قصتنا</a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-end mb-16">
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">مختار بعناية</p>
                <h2 class="text-4xl md:text-5xl font-serif">قطع مختارة</h2>
            </div>
            <a href="{{ route('catalog') }}" class="hidden md:block text-gold hover:text-gold-light transition-colors">عرض الكل</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($featuredProducts as $product)
            <div class="product-card card-luxury p-4 group">
                <a href="{{ route('product', ['id' => $product->id]) }}" class="product-card-image block mb-4 relative overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="text-white text-sm border border-white px-4 py-2">عرض التفاصيل</span>
                    </div>
                </a>
                <h3 class="font-serif text-lg mb-1">{{ $product->name }}</h3>
                <p class="text-gray-500 text-sm mb-3">{{ $product->gold_purity }} • {{ $product->weight }}g</p>
                <div class="flex justify-between items-center">
                    <span class="gold-text font-medium">${{ number_format($product->current_price, 2) }}</span>
                    <button @click.prevent="$store.cart.add({{ $product->id }})" class="w-10 h-10 border border-gray-700 flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-12 text-gray-500">
                <p>لا توجد منتجات مميزة حالياً.</p>
                <a href="{{ route('catalog') }}" class="text-gold hover:underline mt-2 inline-block">تصفح الكتالوج</a>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-8 md:hidden">
            <a href="{{ route('catalog') }}" class="text-gold hover:text-gold-light transition-colors">عرض الكل</a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-black-light relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gold blur-3xl"></div>
    </div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <p class="text-gold uppercase tracking-wider text-sm mb-4">إبدأ رحلتك</p>
        <h2 class="text-4xl md:text-6xl font-serif mb-6">تجربة <span class="gold-gradient">إستثنائية</span></h2>
        <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">
            إنضم إلى آلاف الأشخاص الذين اختاروا سوداقولد للاحتفال بأشهر لحظاتهم الأكثر قيمة.
        </p>
        <a href="{{ route('catalog') }}" class="btn-gold">إكتشف المجوعات الذهبية</a>
    </div>
</section>

<!-- Newsletter -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="max-w-xl mx-auto text-center">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">كن أول من يعلم</p>
            <h2 class="text-3xl md:text-4xl font-serif mb-4">إشترك في النشرة الإخبارية</h2>
            <p class="text-gray-500 mb-8">كن أول من يعلم عن العروض والفعاليات</p>
            <form class="flex flex-col sm:flex-row gap-4">
                <input type="email" placeholder="أدخل بريدك الإلكتروني" class="input-luxury flex-1">
                <button type="submit" class="btn-gold whitespace-nowrap">إشترك</button>
            </form>
        </div>
    </div>
</section>
@endsection
