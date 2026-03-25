@extends('layouts.frontend')

@section('title', 'تواصل معنا')

@section('content')
<!-- Hero Section -->
<section class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-black via-black to-gray-900"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-gold-dark rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <p class="text-gold uppercase tracking-wider text-sm mb-6">تواصل معنا</p>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6">
            <span class="gold-gradient">اتصل</span>
            <span class="white">بنا</span>
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto">
            نود أن نسمع منك. تواصل معنا لأي استفسارات أو مساعدة
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Form -->
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">أرسل رسالة</p>
                <h2 class="text-4xl md:text-5xl font-serif mb-6">تواصل معنا</h2>
                <p class="text-gray-400 mb-8">
                    لديك سؤال عن منتجاتنا؟ تحتاج مساعدة في طلب؟ املأ النموذج أدناه وسنعود إليك في أقرب وقت ممكن.
                </p>
                
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">الاسم الأول</label>
                            <input type="text" class="input-luxury w-full" placeholder="قصي">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">اسم العائلة</label>
                            <input type="text" class="input-luxury w-full" placeholder="ربيع">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">البريد الإلكتروني</label>
                        <input type="email" class="input-luxury w-full" placeholder="you@example.com">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">رقم الهاتف</label>
                        <input type="tel" class="input-luxury w-full" placeholder="+249 000 000 000">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">الموضوع</label>
                        <select class="input-luxury w-full">
                            <option value="">اختر موضوعاً</option>
                            <option value="general">استفسار عام</option>
                            <option value="order">حالة الطلب</option>
                            <option value="product">معلومات المنتج</option>
                            <option value="custom">طلب مخصص</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">الرسالة</label>
                        <textarea rows="5" class="input-luxury w-full" placeholder="كيف يمكننا مساعدتك؟"></textarea>
                    </div>
                    <button type="submit" class="btn-gold w-full">إرسال الرسالة</button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div>
                <div class="space-y-8">
                    <div class="card-luxury p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
<h3 class="font-serif text-lg mb-2">زورنا</h3>
                                <p class="text-gray-400">
                                    الخرطوم<br>
                                    الخرطوم<br>
                                    السودان
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-luxury p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-serif text-lg mb-2">راسلنا</h3>
                                <p class="text-gray-400">
                                    contact@sudagold.com<br>
                                    support@sudagold.com
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-luxury p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-serif text-lg mb-2">اتصل بنا</h3>
                                <p class="text-gray-400">
                                    +249 995743687<br>
                                    <span class="text-sm">السبت - الخميس: 8ص - 6م</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-luxury p-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-gold/10 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-serif text-lg mb-2">ساعات العمل</h3>
                                <p class="text-gray-400">
                                    الأحد - الخميس: 8ص - 6م<br>
                                    السبت: 2م - 6م<br>
                                    الجمعة: مغلق
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="mt-8">
                    <h3 class="font-serif text-lg mb-4">تابعنا</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.85.38-1.78.64-2.75.76 1-.6 1.76-1.55 2.12-2.68-.93.55-1.96.95-3.06 1.17-.88-.94-2.13-1.53-3.51-1.53-2.66 0-4.81 2.16-4.81 4.81 0 .38.04.75.13 1.1-4-.2-7.58-2.11-9.96-5.02-.42.72-.66 1.56-.66 2.46 0 1.68.85 3.16 2.14 4.02-.79-.02-1.53-.24-2.18-.6v.06c0 2.35 1.67 4.31 3.88 4.76-.4.1-.83.16-1.27.16-.31 0-.62-.03-.92-.08.63 1.96 2.45 3.39 4.61 3.43-1.69 1.32-3.83 2.1-6.15 2.1-.4 0-.8-.02-1.19-.07 2.19 1.4 4.78 2.22 7.57 2.22 9.07 0 14.02-7.52 14.02-14.02 0-.21 0-.43-.01-.64.96-.69 1.79-1.56 2.45-2.55z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:border-gold hover:text-gold transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="h-96 bg-black-light">
    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
        <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-gray-500">خريطة الموقع متاحة</p>
        </div>
    </div>
</section>
@endsection
