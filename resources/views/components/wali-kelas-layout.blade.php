<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Wali Kelas' }} - Classly</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        <aside class="flex flex-col w-64 bg-white border-r border-gray-200 shrink-0 h-screen sticky top-0">

            <div class="flex flex-col items-center justify-center py-6 border-b border-gray-200">
                <img src="{{ asset('images/logo.png') }}" alt="Classly" class="h-10 w-auto">
                <span class="mt-2 text-lg font-bold tracking-tight text-slate-800">Classly</span>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-4 border-b border-gray-200 hover:bg-gray-50">
                <x-avatar :user="auth()->user()" />
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">Wali Kelas</p>
                </div>
            </a>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                    $menu = [
                        ['group' => 'wali_kelas.dashboard', 'route' => 'wali_kelas.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
                        ['group' => 'wali_kelas.presensi.*', 'route' => 'wali_kelas.presensi.index', 'label' => 'Presensi', 'icon' => 'clipboard'],
                        ['group' => 'wali_kelas.kegiatan.*', 'route' => 'wali_kelas.kegiatan.index', 'label' => 'Kegiatan', 'icon' => 'flag'],
                    ];
                    $icons = [
                        'home' => 'M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m5 0h5a1 1 0 001-1V10',
                        'clipboard' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4',
                        'flag' => 'M5 3v18M5 4h11l-2 4 2 4H5',
                    ];
                @endphp

                @foreach ($menu as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                           {{ request()->routeIs($item['group'])
                               ? 'bg-amber-300 text-slate-900'
                               : 'text-slate-500 hover:bg-gray-50 hover:text-slate-800' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="p-3 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-medium text-slate-500 hover:bg-gray-50">
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <header class="bg-white border-b border-gray-200 flex items-center justify-between px-8 py-4 shrink-0">
                <div class="flex items-baseline gap-3">
                    <span class="text-2xl font-bold text-slate-900">{{ config('classly.nama_sekolah') }}</span>
                    <span class="text-sm text-slate-400">{{ $title ?? 'Dashboard' }}</span>
                </div>
            </header>

            <main class="flex-1 p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>