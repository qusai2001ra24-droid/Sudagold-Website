@extends('layouts.frontend')

@section('title', 'تحليلات الذهب - سوداقولد')

@push('styles')
<style>
    .gold-card {
        @apply bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-2xl p-6;
    }
    .price-ticker {
        @apply flex items-center gap-3 py-2 px-4 bg-black/50 rounded-lg border border-gray-800;
    }
    .live-dot {
        animation: pulse-gold 2s infinite;
    }
    @keyframes pulse-gold {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .stat-card {
        @apply bg-gradient-to-br from-gray-900 to-black border border-gray-800 rounded-xl p-5 text-center;
    }
    .guide-section {
        @apply bg-gray-900/50 border border-gray-800 rounded-2xl p-8;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="pt-32 pb-16 bg-black relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-gold-dark rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-gold/10 border border-gold/30 rounded-full mb-6">
                <span class="w-2 h-2 bg-gold rounded-full live-dot"></span>
                <span class="text-gold text-sm font-medium">تحديث مباشر للأسعار</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-serif mb-4">
                <span class="gold-gradient">تحليلات الذهب</span>
            </h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                اسعار الذهب الحية، دليل شامل عن الذهب والتعدين، وتحليلات السوق السوداني
            </p>
        </div>
    </div>
</section>

<!-- Live Gold Prices -->
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center mb-8">
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-2">الأسعار الحالية</p>
                <h2 class="text-3xl font-serif">أسعار الذهب الفورية</h2>
            </div>
            <div class="flex items-center gap-2 text-gray-400 text-sm" x-data="{ lastUpdate: '{{ now()->format('H:i:s') }}' }">
                <span class="w-2 h-2 bg-green-500 rounded-full live-dot"></span>
                <span>آخر تحديث: <span x-text="lastUpdate"></span></span>
                <button @click="fetchPrices()" class="text-gold hover:text-gold-light mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12" x-data="goldPrices()" x-init="init()">
            <template x-for="price in prices" :key="price.purity">
                <div class="gold-card">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-gold text-2xl font-serif" x-text="price.purity"></p>
                            <p class="text-gray-500 text-sm">عيار الذهب</p>
                        </div>
                        <span class="w-3 h-3 bg-green-500 rounded-full live-dot"></span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-400">سعر الجرام</span>
                            <span class="gold-text font-medium" x-text="'$' + parseFloat(price.price_per_gram).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">سعر الأونصة</span>
                            <span class="text-white" x-text="'$' + parseFloat(price.price_per_ounce || 0).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">سعر التولة</span>
                            <span class="text-white" x-text="'$' + parseFloat(price.price_per_tola || 0).toFixed(2)"></span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-800">
                        <p class="text-gray-500 text-xs" x-text="'من المصدر: ' + (price.source || 'سوداقولد')"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Sudanese Gold Market -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="gold-card">
                <h3 class="text-xl font-serif mb-6 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    السوق السوداني للذهب
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-800">
                        <span class="text-gray-400">الانتاج السنوي</span>
                        <span class="gold-text font-medium">~80 طن</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-800">
                        <span class="text-gray-400">أهم مناطق التعدين</span>
                        <span class="text-white">البحر الأحمر، دارفور، كردفان</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-800">
                        <span class="text-gray-400">العملة المحلية</span>
                        <span class="text-white">الجنيه السوداني (SDG)</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-800">
                        <span class="text-gray-400">الهيئة الرقابية</span>
                        <span class="text-white">شركة Sudanese Gold Corporation</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-400">نظام الاستثمار</span>
                        <span class="text-white">حرية التصدير والاستيراد</span>
                    </div>
                </div>
            </div>

            <div class="gold-card">
                <h3 class="text-xl font-serif mb-6 flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    إحصائيات سريعة
                </h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="stat-card">
                        <p class="text-3xl font-serif gold-gradient">{{ $stats['total_products'] }}</p>
                        <p class="text-gray-500 text-sm mt-1">منتج ذهب</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-3xl font-serif gold-gradient">${{ number_format($stats['avg_gold_price'] ?? 0, 2) }}</p>
                        <p class="text-gray-500 text-sm mt-1">متوسط سعر الجرام</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-3xl font-serif gold-gradient">${{ number_format($stats['highest_purity'] ?? 0, 2) }}</p>
                        <p class="text-gray-500 text-sm mt-1">أعلى سعر (24k)</p>
                    </div>
                    <div class="stat-card">
                        <p class="text-3xl font-serif gold-gradient">${{ number_format($stats['lowest_purity'] ?? 0, 2) }}</p>
                        <p class="text-gray-500 text-sm mt-1">أقل سعر (10k)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gold Guide Section -->
<section class="py-16 bg-black-light">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">دليل شامل</p>
            <h2 class="text-4xl font-serif">كل ما تحتاج معرفته عن الذهب</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- What is Gold -->
            <div class="guide-section">
                <h3 class="text-2xl font-serif mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-gold/20 rounded-lg flex items-center justify-center text-gold">۱</span>
                    ما هو الذهب؟
                </h3>
                <div class="prose prose-invert prose-gold max-w-none">
                    <p class="text-gray-400 leading-relaxed mb-4">
                        الذهب هو عنصر كيميائي يحمل الرمز Au (من الكلمة اللاتينية Aurum) وهو من أثمن المعادن على وجه الأرض. يتميز بلونه الأصفر المميز ولمعانه الذي لا يضاهى، كما أنه من أندر المعادن وأغلاها ثمناً.
                    </p>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        يتميز الذهب بعدة خصائص فريدة تجعله مثالياً لصناعة المجوهرات والاستثمار:
                    </p>
                    <ul class="text-gray-400 space-y-2 mb-4">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-white">المتانة:</strong> لا يصدأ ولا يتآكل مع مرور الوقت</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-white">القابلية للتشكيل:</strong> يمكن تشكيله بأشكال دقيقة ومعقدة</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-white">اللمعان:</strong> يفرض بريقاً وجمالاً لا مثيل له</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span><strong class="text-white">القيمة:</strong> يحتفظ بقيمته عبر الزمن ويعتبر ملاذاً آمناً</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Gold Purity -->
            <div class="guide-section">
                <h3 class="text-2xl font-serif mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-gold/20 rounded-lg flex items-center justify-center text-gold">۲</span>
                    نقاء الذهب
                </h3>
                <div class="overflow-hidden">
                    <table class="w-full text-right">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="py-3 px-4 text-gray-400 text-sm font-medium">العيار</th>
                                <th class="py-3 px-4 text-gray-400 text-sm font-medium">النقاء</th>
                                <th class="py-3 px-4 text-gray-400 text-sm font-medium">الاستخدام</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-gray-800">
                                <td class="py-3 px-4 gold-text font-medium">24 قيراط</td>
                                <td class="py-3 px-4 text-white">99.9%</td>
                                <td class="py-3 px-4 text-gray-400">سبائك، عملات، مجوهرات فاخرة</td>
                            </tr>
                            <tr class="border-b border-gray-800">
                                <td class="py-3 px-4 gold-text font-medium">22 قيراط</td>
                                <td class="py-3 px-4 text-white">91.6%</td>
                                <td class="py-3 px-4 text-gray-400">مجوهرات تقليدية</td>
                            </tr>
                            <tr class="border-b border-gray-800">
                                <td class="py-3 px-4 gold-text font-medium">18 قيراط</td>
                                <td class="py-3 px-4 text-white">75%</td>
                                <td class="py-3 px-4 text-gray-400">مجوهرات شائعة، سوداقولد</td>
                            </tr>
                            <tr class="border-b border-gray-800">
                                <td class="py-3 px-4 gold-text font-medium">14 قيراط</td>
                                <td class="py-3 px-4 text-white">58.3%</td>
                                <td class="py-3 px-4 text-gray-400">مجوهرات يومية</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 gold-text font-medium">10 قيراط</td>
                                <td class="py-3 px-4 text-white">41.7%</td>
                                <td class="py-3 px-4 text-gray-400">مجوهرات اقتصادية</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-gray-500 text-sm mt-4">
                    العيار (%) = (النقاء / 24) × 100
                </p>
            </div>

            <!-- Gold Mining -->
            <div class="guide-section">
                <h3 class="text-2xl font-serif mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-gold/20 rounded-lg flex items-center justify-center text-gold">۳</span>
                    تعدين الذهب
                </h3>
                <div class="prose prose-invert max-w-none">
                    <p class="text-gray-400 leading-relaxed mb-4">
                        تعدين الذهب هو عملية استخراج الذهب من باطن الأرض. يوجد الذهب في صخور القشرة الأرضية على شكل عروق أو حبيبات موزعة. تختلف طرق التعدين حسب نوع الرواسب وعمقها.
                    </p>
                    <h4 class="text-lg font-serif text-white mb-3">طرق التعدين الرئيسية:</h4>
                    <div class="space-y-3">
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-2">التعدين الحرفي (Artisanal Mining)</h5>
                            <p class="text-gray-400 text-sm">طريقة تقليدية يستخدمها المعدنون الحرفيون باستخدام أدوات بسيطة مثل المجارف والفؤوس والغربالات. هذا هو النوع السائد في السودان.</p>
                        </div>
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-2">التعدين السطحي (Open Pit)</h5>
                            <p class="text-gray-400 text-sm">يتم إزالة طبقات الصخور العلوية للوصول إلى رواسب الذهب القريبة من السطح. تستخدم في الرواسب الكبيرة.</p>
                        </div>
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-2">التعدين تحت السطحي (Underground)</h5>
                            <p class="text-gray-400 text-sm">حفر أنفاق وثقوب للوصول إلى العروق العميقة. يتطلب معدات متطورة واستثماراً كبيراً.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gold in Sudan -->
            <div class="guide-section">
                <h3 class="text-2xl font-serif mb-6 flex items-center gap-3">
                    <span class="w-10 h-10 bg-gold/20 rounded-lg flex items-center justify-center text-gold">٤</span>
                    الذهب في السودان
                </h3>
                <div class="prose prose-invert max-w-none">
                    <p class="text-gray-400 leading-relaxed mb-4">
                        يُعتبر السودان من أكبر منتجي الذهب في أفريقيا والعالم العربي. بدأ التنقيب عن الذهب في السودان بشكل رسمي في ستينيات القرن العشرين، وتطور ليصبح ركيزة أساسية للاقتصاد الوطني.
                    </p>
                    
                    <h4 class="text-lg font-serif text-white mb-3">أهم مناطق التعدين:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-1">ولاية البحر الأحمر</h5>
                            <p class="text-gray-500 text-sm">أكبر منطقة إنتاجية، منجم حلايب الشهير</p>
                        </div>
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-1">دارفور</h5>
                            <p class="text-gray-500 text-sm">مناطق تعدين متعددة في ولايات دارفور</p>
                        </div>
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-1">كردفان</h5>
                            <p class="text-gray-500 text-sm">مناطق غنية بعروق الذهب</p>
                        </div>
                        <div class="bg-black/50 rounded-lg p-4 border border-gray-800">
                            <h5 class="text-gold font-medium mb-1">نهر النيل</h5>
                            <p class="text-gray-500 text-sm">تعدين على ضفاف النيل</p>
                        </div>
                    </div>

                    <h4 class="text-lg font-serif text-white mb-3">التحديات والحلول:</h4>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-gold mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-gray-400"><strong class="text-white">التحدي:</strong> التعدين العشوائي</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-400"><strong class="text-white">الحل:</strong> التنظيم الحكومي وتطبيق التراخيص</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Buying Guide -->
<section class="py-16 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">قبل الشراء</p>
            <h2 class="text-4xl font-serif">دليل شراء الذهب</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 bg-gradient-to-b from-gray-900 to-black border border-gray-800 rounded-2xl">
                <div class="w-16 h-16 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-serif mb-4">تحقق من العيار</h3>
                <p class="text-gray-400">تأكد من وجود ختم العيار على القطعة. العيار 18 و 24 هما الأكثر شيوعاً للمجوهرات الفاخرة.</p>
            </div>

            <div class="text-center p-8 bg-gradient-to-b from-gray-900 to-black border border-gray-800 rounded-2xl">
                <div class="w-16 h-16 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-serif mb-4">قارن الأسعار</h3>
                <p class="text-gray-400">أسعار الذهب تتغير يومياً. قارن الأسعار من مصادر متعددة قبل الشراء ولا تقع في فخ الأسعار المنخفضة جداً.</p>
            </div>

            <div class="text-center p-8 bg-gradient-to-b from-gray-900 to-black border border-gray-800 rounded-2xl">
                <div class="w-16 h-16 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-serif mb-4">اطلب فاتورة</h3>
                <p class="text-gray-400">احتفظ دائماً بفاتورة الشراء التي توضح العيار والوزن والسعر. هذا يحميك ويوثق عملية الشراء.</p>
            </div>
        </div>
    </div>
</section>

<!-- Investment Section -->
<section class="py-16 bg-black-light">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">استثمار</p>
                <h2 class="text-4xl font-serif mb-6">الذهب كاستثمار آمن</h2>
                <p class="text-gray-400 leading-relaxed mb-6">
                    على مر التاريخ، اعتبر الذهب ملاذاً آمناً للمستثمرين. في أوقات الأزمات الاقتصادية وعدم الاستقرار، يميل سعر الذهب للصعود حيث يلجأ المستثمرون إليه لحماية ثرواتهم.
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-gold/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">حماية من التضخم</h4>
                            <p class="text-gray-400 text-sm">الذهب يحتفظ بقيمته الحقيقية عند ارتفاع أسعار المستهلك</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-gold/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">تنويع المحفظة</h4>
                            <p class="text-gray-400 text-sm">يقلل من المخاطر الإجمالية عند إضافته لمحافظ الاستثمار</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 bg-gold/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-4 h-4 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-medium mb-1">سيولة عالية</h4>
                            <p class="text-gray-400 text-sm">يمكن تحويله إلى نقد بسهولة في أي وقت</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-black border border-gray-800 rounded-2xl p-8">
                <h3 class="text-xl font-serif mb-6">أشكال الاستثمار في الذهب</h3>
                <div class="space-y-4">
                    <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                        <h4 class="text-gold font-medium mb-2">المجوهرات</h4>
                        <p class="text-gray-400 text-sm">جمع المجوهرات الذهبية للزينة والقيمة. عيار 18 و 22 الأكثر شيوعاً.</p>
                    </div>
                    <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                        <h4 class="text-gold font-medium mb-2">السبائك</h4>
                        <p class="text-gray-400 text-sm">قطع ذهبية نقية (24 قيراط) بأوزان مختلفة من 1 جرام إلى كيلوغرام.</p>
                    </div>
                    <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                        <h4 class="text-gold font-medium mb-2">العملات</h4>
                        <p class="text-gray-400 text-sm">عملات ذهبية مثل Krugerrand و American Eagle.</p>
                    </div>
                    <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                        <h4 class="text-gold font-medium mb-2">الصناديق المتداولة (ETFs)</h4>
                        <p class="text-gray-400 text-sm">وحدات في صناديق تستثمر في الذهب الفعلي.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-black relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gold blur-3xl"></div>
    </div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-serif mb-6">جاهز لاكتشاف <span class="gold-gradient">مجوهراتنا الذهبية</span>؟</h2>
        <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
            تصفح مجموعتنا الفاخرة من المجوهرات الذهبية المصنوعة بعناية فائقة.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('catalog') }}" class="btn-gold">تصفح المنتجات</a>
            <a href="{{ route('contact') }}" class="btn-outline-gold">تواصل معنا</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function goldPrices() {
    return {
        prices: [],
        init() {
            this.fetchPrices();
            setInterval(() => this.fetchPrices(), 30000);
        },
        async fetchPrices() {
            try {
                const response = await fetch('/api/gold-prices');
                const data = await response.json();
                this.prices = data.prices;
            } catch (error) {
                console.error('Failed to fetch gold prices:', error);
            }
        }
    }
}
</script>
@endpush
