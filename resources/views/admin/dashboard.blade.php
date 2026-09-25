<x-admin-layout title="Dashboard Admin">
    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Kartu statistik --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wide">Total Guru</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ $totalGuru }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wide">Total Kelas</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ $totalKelas }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wide">Total Siswa</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-slate-400 uppercase tracking-wide">Hadir Hari Ini</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">
                    {{ $hadirHariIni }}
                    @if ($perluVerifikasiHariIni > 0)
                        <span class="text-sm font-medium text-amber-600">({{ $perluVerifikasiHariIni }} perlu verifikasi)</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Guru terbaru --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-slate-800">Guru Terbaru</h2>
                    <a href="{{ route('admin.guru.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse ($guruTerbaru as $g)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-700">{{ $g->name }}</span>
                            <span class="text-slate-400">{{ $g->email }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada guru.</p>
                    @endforelse
                </div>
            </div>

            {{-- Ringkasan kelas --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-slate-800">Kelas</h2>
                    <a href="{{ route('admin.kelas.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse ($kelasRingkas as $k)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-700">{{ $k->nama }}</span>
                            <span class="text-slate-400">{{ $k->siswa_count }} siswa &middot; {{ $k->waliKelas->name ?? 'Belum ada wali kelas' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada kelas.</p>
                    @endforelse
                </div>
            </div>

            {{-- Jadwal hari ini --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-slate-800">Jadwal Hari Ini</h2>
                    <a href="{{ route('admin.jadwal.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse ($jadwalHariIni as $j)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-700">{{ $j->kelas->nama }} &middot; {{ $j->mapel }}</span>
                            <span class="text-slate-400">{{ substr($j->jam_mulai, 0, 5) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Tidak ada jadwal hari ini.</p>
                    @endforelse
                </div>
            </div>

            {{-- Kegiatan terdekat --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-slate-800">Kegiatan Terdekat</h2>
                    <a href="{{ route('admin.kegiatan.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse ($kegiatanTerdekat as $k)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-700">{{ $k->judul }}</span>
                            <span class="text-slate-400">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Tidak ada kegiatan mendatang.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-admin-layout>