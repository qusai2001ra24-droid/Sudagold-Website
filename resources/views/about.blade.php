@extends('layouts.frontend')

@section('title', 'عن سوداقولد')

@section('content')
<!-- Hero Section -->
<section class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-black via-black to-gray-900"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gold rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-gold-dark rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
        <p class="text-gold uppercase tracking-wider text-sm mb-6">قصتنا</p>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6">
            <span class="gold-gradient">عن سوداقولد</span>
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto">
            اكتشف تراث الحرفية والأناقة الذي يُعرّف سوداقولد.
        </p>
    </div>
</section>

<!-- Story Section -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="aspect-square relative">
                    <img src="https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?w=800&h=800&fit=crop" alt="حرفنا" class="w-full h-full object-cover">
                    <div class="absolute -bottom-6 -right-6 w-48 h-48 border border-gold/30"></div>
                    <div class="absolute -top-6 -left-6 w-32 h-32 border border-gold/20"></div>
                </div>
            </div>
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">{{ __('تراثنا') }}</p>
                <h2 class="text-4xl md:text-5xl font-serif mb-6">{{ __('تراثنا') }}</h2>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    {{ __('تأسست سوداغولد برؤية لإعادة تعريف المجوهرات الفاخرة، وكانت دائماً منارة للتميز في عالم صناعة الذهب الفاخر. بدأت رحلتنا بإيمان بسيط: أن كل قطعة مجوهرات يجب أن تكون تحفة فنية، مصنوعة بشغف ودقة.') }}
                </p>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    {{ __('اليوم، نواصل هذه التقاليد بالجمع بين الأساليب التقليدية والتصميم المعاصر. كل إبداع يغادر ورشتنا يحمل ثقل خبرة الأجيال ولمعة الابتكار النقي.') }}
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('catalog') }}" class="btn-gold">{{ __('استكشف المجموعة') }}</a>
                    <a href="{{ route('contact') }}" class="btn-outline-gold">{{ __('تواصل معنا') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-24 bg-black-light">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-gold uppercase tracking-wider text-sm mb-4">{{ __('ما نؤمن به') }}</p>
            <h2 class="text-4xl md:text-5xl font-serif">{{ __('قيمنا') }}</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card-luxury p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-gold/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <h3 class="font-serif text-xl mb-4">{{ __('الجودة') }}</h3>
                <p class="text-gray-400">{{ __('نستخدم فقط أجود أنواع الذهب عيار 18 و 24 قيراط، المصدرة بشكل أخلاقي والمصنوعة بدقة لضمان جمال يدوم.') }}</p>
            </div>
            
            <div class="card-luxury p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-gold/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="font-serif text-xl mb-4">{{ __('الشغف') }}</h3>
                <p class="text-gray-400">{{ __('كل قطعة مصنوعة بحب وإخلاص، تحويل الذهب الخام إلى تحف فنية.') }}</p>
            </div>
            
            <div class="card-luxury p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-gold/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-serif text-xl mb-4">{{ __('التقليد') }}</h3>
                <p class="text-gray-400">{{ __('نحترم عقود من الخبرة في صناعة المجوهرات مع تبني الابتكار الحديث.') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <span class="text-4xl md:text-5xl font-serif gold-gradient block">500+</span>
                <span class="text-gray-500 mt-2 block">{{ __('قطع فريدة') }}</span>
            </div>
            <div class="text-center">
                <span class="text-4xl md:text-5xl font-serif gold-gradient block">50+</span>
                <span class="text-gray-500 mt-2 block">{{ __('حرفيون ماهرون') }}</span>
            </div>
            <div class="text-center">
                <span class="text-4xl md:text-5xl font-serif gold-gradient block">10k+</span>
                <span class="text-gray-500 mt-2 block">{{ __('عملاء سعداء') }}</span>
            </div>
            <div class="text-center">
                <span class="text-4xl md:text-5xl font-serif gold-gradient block">15+</span>
                <span class="text-gray-500 mt-2 block">{{ __('سنوات الخبرة') }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Craftsmanship Section -->
<section class="py-24 bg-black-light">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-gold uppercase tracking-wider text-sm mb-4">{{ __('الخبرة') }}</p>
                <h2 class="text-4xl md:text-5xl font-serif mb-6">{{ __('الحرفية') }}</h2>
                <p class="text-gray-400 mb-6 leading-relaxed">
                    {{ __('يقدم حرفيونا الماهرون عقوداً من الخبرة في كل قطعة ينشئونها. كل مجوهرات تمر بعملية دقيقة من التصميم، الصب، التلميع وفحص الجودة.') }}
                </p>
                <p class="text-gray-400 mb-8 leading-relaxed">
                    {{ __('نؤمن أن الفخار الحقيقي يكمن في التفاصيل. من أصغر قطعة إلى أطول قلادة، كل عنصر يتلقى نفس مستوى من الاهتمام والعناية.') }}
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-300">{{ __('ذهب مستدام المصدر') }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-300">{{ __('تميز بالحرفية') }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-300">{{ __('ضمان مدى الحياة') }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gold flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-300">{{ __('تغيير المقاس مجاني') }}</span>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-[4/5]">
                    <img src="https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?w=800&h=1000&fit=crop" alt="{{ __('Craftsmanship') }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-black relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gold blur-3xl"></div>
    </div>
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-4xl md:text-6xl font-serif mb-6">{{ __('عش') }} <span class="gold-gradient">{{ __('الاستثنائية') }}</span></h2>
        <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">
            {{ __('زور معرضنا أو تواصل معنا لاكتشاف القطعة المثالية لمجموعتك.') }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('catalog') }}" class="btn-gold">{{ __('استكشف المجموعة') }}</a>
            <a href="{{ route('contact') }}" class="btn-outline-gold">{{ __('اتصل بنا') }}</a>
        </div>
    </div>
</section>
@endsection
