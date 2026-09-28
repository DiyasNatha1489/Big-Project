<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Classly</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #111827 !important;
            transition: background-color 9999s ease-in-out 0s;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-[100dvh] flex flex-col lg:flex-row lg:justify-center lg:gap-16 lg:h-screen lg:min-h-0">

        {{-- Form login --}}
        <div class="flex items-center justify-center lg:w-96 lg:shrink-0 px-8 pt-8 pb-4 lg:px-0 lg:py-4 lg:overflow-y-auto">
            <div class="w-full max-w-sm">

                <h1 class="font-serif text-2xl lg:text-3xl font-bold text-black mb-6 text-center lg:text-left">Selamat Datang</h1>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <div class="relative">
                            <label for="email" class="absolute -top-2.5 left-5 bg-white px-2 font-serif text-sm text-gray-400">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="block w-full rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm focus:border-gray-400 focus:ring-0">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 ml-5 text-xs" />
                    </div>

                    <div>
                        <div class="relative">
                            <label for="password" class="absolute -top-2.5 left-5 bg-white px-2 font-serif text-sm text-gray-400">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="block w-full rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm focus:border-gray-400 focus:ring-0">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 ml-5 text-xs" />
                    </div>

                    <div class="flex items-center justify-between px-2">
                        <label class="flex items-center gap-2 text-xs text-gray-500">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#385C78] focus:ring-0">
                            Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-gray-600">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full rounded-full bg-[#E8D48A] py-2.5 text-sm font-medium text-white hover:bg-[#dcc673] transition">
                        Login
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="mt-4 text-center font-serif text-sm text-gray-500">atau</p>
                    <p class="mt-1 text-center text-xs text-gray-400">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-medium text-[#385C78] hover:underline">Daftar dengan kode kelas</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- Kubah biru: mengisi sisa tinggi layar --}}
        <div class="flex-1 lg:flex-none flex justify-center pt-6 lg:pt-5">
            <div class="w-64 sm:w-72 lg:w-80 min-h-[14rem] bg-[#385C78] rounded-t-full flex flex-col items-center pt-10 lg:pt-16">

                <div class="w-24 h-24 lg:w-32 lg:h-32 rounded-full bg-white flex items-center justify-center shadow-md">
                    <img src="{{ asset('images/logo.png') }}" alt="Classly" class="w-14 h-14 lg:w-20 lg:h-20 object-contain">
                </div>

                <p class="mt-4 lg:mt-6 text-white text-xl lg:text-2xl font-bold">Classly</p>
            </div>
        </div>
    </div>
</body>
</html>