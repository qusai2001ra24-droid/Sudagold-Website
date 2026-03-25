@extends('layouts.frontend')

@section('title', __('Create Account'))

@section('content')
<!-- Register Section -->
<section class="min-h-screen flex items-center justify-center py-20 bg-black relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-[#d4af37] rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 left-1/4 w-64 h-64 bg-[#d4af37] rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-[#0f0f0f] border border-[#1f1f1f] rounded-2xl p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                    <span class="text-3xl font-serif font-bold text-[#d4af37]">سوداقولد</span>
                </a>
                <h1 class="text-2xl font-serif text-white">{{ __('Create Account') }}</h1>
                <p class="text-gray-500 mt-2">{{ __('Join us and discover luxury gold jewelry') }}</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="text-gray-400 text-sm mb-2 block">{{ __('First Name') }}</label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="input-luxury w-full" placeholder="{{ __('First Name') }}">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="text-gray-400 text-sm mb-2 block">{{ __('Last Name') }}</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                            class="input-luxury w-full" placeholder="{{ __('Last Name') }}">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="text-gray-400 text-sm mb-2 block">{{ __('Email Address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="input-luxury w-full" placeholder="{{ __('Email Address') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="text-gray-400 text-sm mb-2 block">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required
                        class="input-luxury w-full" placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="text-gray-400 text-sm mb-2 block">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="input-luxury w-full" placeholder="••••••••">
                    @error('password_confirmation')
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
                    {{ __('Create Account') }}
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-500">{{ __('Already have an account?') }} 
                    <a href="{{ route('login') }}" class="text-[#d4af37] hover:text-[#e8c547]">{{ __('Sign in') }}</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
