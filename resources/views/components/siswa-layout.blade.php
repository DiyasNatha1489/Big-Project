<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Siswa' }} - Classly</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        <aside class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200 shrink-0 h-screen sticky top-0">

            <div class="flex flex-col items-center justify-center py-6 border-b border-gray-200">
                <img src="{{ asset('images/logo.png') }}" alt="Classly" class="h-10 w-auto">
                <span class="mt-2 text-lg font-bold tracking-tight text-slate-800">Classly</span>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-6 py-4 border-b border-gray-200 hover:bg-gray-50">
                <x-avatar :user="auth()->user()" size="w-10 h-10" />
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()->kelas->nama ?? 'Belum ada kelas' }}</p>
                </div>
            </a>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                    $menu = [
                        ['group' => 'siswa.dashboard', 'route' => 'siswa.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
                        ['group' => 'siswa.absen.*', 'route' => 'siswa.absen.create', 'label' => 'Absen', 'icon' => 'check'],
                        ['group' => 'siswa.jadwal.*', 'route' => 'siswa.jadwal.index', 'label' => 'Jadwal', 'icon' => 'calendar'],
                        ['group' => 'siswa.kegiatan.*', 'route' => 'siswa.kegiatan.index', 'label' => 'Kegiatan', 'icon' => 'flag'],
                    ];
                    $icons = [
                        'home' => 'M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m5 0h5a1 1 0 001-1V10',
                        'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a1 1 0 001-1V7a1 1 0 00-1-1H5a1 1 0 00-1 1v13a1 1 0 001 1z',
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
            <header class="bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 py-4 shrink-0">
                <div class="flex items-baseline gap-2 lg:gap-3 min-w-0">
                    <span class="text-lg lg:text-2xl font-bold text-slate-900 truncate">{{ config('classly.nama_sekolah') }}</span>
                    <span class="hidden sm:inline text-sm text-slate-400 truncate">{{ $title ?? 'Dashboard' }}</span>
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-8 pb-24 lg:pb-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <nav class="lg:hidden fixed bottom-4 left-1/2 -translate-x-1/2 z-40 bg-slate-800 rounded-full shadow-lg flex items-center gap-1 px-2 py-2">
        @php
            $bottomMenu = [
                ['group' => 'siswa.dashboard', 'route' => 'siswa.dashboard', 'icon' => 'home'],
                ['group' => 'siswa.absen.*', 'route' => 'siswa.absen.create', 'icon' => 'check'],
                ['group' => 'siswa.jadwal.*', 'route' => 'siswa.jadwal.index', 'icon' => 'calendar'],
                ['group' => 'siswa.kegiatan.*', 'route' => 'siswa.kegiatan.index', 'icon' => 'flag'],
            ];
            $bottomIcons = [
                'home' => 'M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5m5 0h5a1 1 0 001-1V10',
                'check' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a1 1 0 001-1V7a1 1 0 00-1-1H5a1 1 0 00-1 1v13a1 1 0 001 1z',
                'flag' => 'M5 3v18M5 4h11l-2 4 2 4H5',
            ];
        @endphp

        @foreach ($bottomMenu as $item)
            <a href="{{ route($item['route']) }}"
            class="w-12 h-12 rounded-full flex items-center justify-center transition
                {{ request()->routeIs($item['group']) ? 'bg-amber-300 text-slate-900' : 'text-slate-300 hover:bg-slate-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $bottomIcons[$item['icon']] }}" />
                </svg>
            </a>
        @endforeach

        <a href="{{ route('profile.edit') }}"
        class="w-12 h-12 rounded-full flex items-center justify-center transition {{ request()->routeIs('profile.edit') ? 'ring-2 ring-amber-300' : '' }}">
            <x-avatar :user="auth()->user()" size="w-8 h-8" />
        </a>
    </nav>
</body>
</html>