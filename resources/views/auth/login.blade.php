<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Fonts & Vite Assets -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-100 bg-slate-900">
    <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">
        
        <div class="w-full max-w-4xl bg-slate-800 shadow-2xl rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-slate-700 my-auto">
            
            <!-- Sisi Kiri: Banner / Brand -->
            <div class="hidden md:flex flex-col justify-between p-8 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-900 text-white relative overflow-hidden">
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                
                <div>
                    <span class="text-xs font-semibold tracking-widest uppercase bg-white/20 px-3 py-1 rounded-full backdrop-blur-md">
                        GOR Booking System
                    </span>
                    <h2 class="text-3xl font-extrabold mt-6 leading-tight">Selamat Datang Kembali!</h2>
                    <p class="mt-2 text-indigo-100 text-sm">Kelola pemesanan lapangan dan laporan keuangan Anda dalam satu dasbor terpadu.</p>
                </div>

                <div class="text-xs text-indigo-200">
                    &copy; {{ date('Y') }} Sistem Manajemen GOR. All rights reserved.
                </div>
            </div>

            <!-- Sisi Kanan: Form Login -->
            <div class="p-8 sm:p-10 flex flex-col justify-center">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-white tracking-tight">Masuk ke Akun</h1>
                    <p class="text-sm text-slate-400 mt-1">Masukkan kredensial Anda untuk melanjutkan</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-slate-300 font-medium" />
                        <x-text-input id="email" 
                            class="block mt-1 w-full bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required autofocus autocomplete="username" 
                            placeholder="nama@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-slate-300 font-medium" />
                        <x-text-input id="password" 
                            class="block mt-1 w-full bg-slate-900 border-slate-700 text-slate-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" 
                            placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div class="flex items-center justify-between text-sm pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-900 text-indigo-500 shadow-sm focus:ring-indigo-500 focus:ring-offset-slate-800" name="remember">
                            <span class="ms-2 text-xs text-slate-400">{{ __('Ingat saya') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs text-indigo-400 hover:text-indigo-300 transition duration-150 ease-in-out" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow-lg hover:shadow-indigo-500/25 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-800">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</body>
</html>