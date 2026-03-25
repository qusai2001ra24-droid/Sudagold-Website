@extends('layouts.frontend')

@section('title', __('تسجيل الدخول'))

@section('content')
<!-- Login Section -->
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
                    <span class="text-3xl font-serif font-bold text-[#d4af37]">سوداقولد</span>
                </a>
                <h1 class="text-2xl font-serif text-white">{{ __('مرحباً بعودتك') }}</h1>
                <p class="text-gray-500 mt-2">{{ __('سجل الدخول لحسابك') }}</p>
            </div>

            <!-- Session Status -->
            @if(session('status'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="text-gray-400 text-sm mb-2 block">{{ __('عنوان البريد الإلكتروني') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('البريد الإلكتروني') }}" required autofocus
                        class="input-luxury w-full" placeholder="{{ __('عنوان البريد الإلكتروني') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="text-gray-400 text-sm mb-2 block">{{ __('كلمة المرور') }}</label>
                    <input id="password" type="password" name="password" required
                        class="input-luxury w-full" placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="w-4 h-4 bg-black border-gray-700 rounded focus:ring-[#d4af37] text-[#d4af37]">
                        <span class="ml-2 text-sm text-gray-400">{{ __('تذكرني') }}</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-[#d4af37] hover:text-[#e8c547]">
                            {{ __('هل نسيت كلمة المرور ؟') }}
                        </a>
                    @endif
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
                    {{ __('تسجيل الدخول') }}
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-500">{{ __('هل لديك حساب مسبقاً ؟') }} 
                    <a href="{{ route('register') }}" class="text-[#d4af37] hover:text-[#e8c547]">{{ __('التسجيل') }}</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
