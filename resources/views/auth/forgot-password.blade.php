@extends('layouts.frontend')

@section('title', __('هل نسيت كلمة المرور ؟'))

@section('content')
<!-- Forgot Password Section -->
<section class="min-h-screen flex items-center justify-center py-20 bg-black relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#d4af37] rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-[#d4af37] rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                    <span class="text-3xl font-serif font-bold text-[#d4af37]">سودا</span>
                    <span class="text-3xl font-serif font-light">قولد</span>
                </a>
                <h1 class="text-2xl font-serif text-white">{{ __('هل نسيت كلمة مرورك ؟') }}</h1>
                <p class="text-gray-500 mt-2">{{ __('لا توجد مشكلة . فقط دعنا نعرف عنوان بريدك الإلكتروني ، و سوف نرسل لك رابط إعادة ضبط كلمة المرور الذي يسمح لك بإختيار كلمة مرور جديدة.') }}</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm">
                    {{ session('الحالة') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="text-gray-400 text-sm mb-2 block">{{ __('عنوان البريد الإلكتروني') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('البريد الإلكتروني') }}" required autofocus
                        class="input-luxury w-full" placeholder="your@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-gold w-full group relative overflow-hidden">
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    <span class="sparkle"></span>
                    {{ __('أرسل رابط إعادة ضبط كلمة المرور') }}
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <p class="text-gray-500">{{ __('لديك حساب بالفعل ؟') }} 
                    <a href="{{ route('login') }}" class="text-[#d4af37] hover:text-[#e8c547]">{{ __('تسجيل الدخول') }}</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
