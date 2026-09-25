<x-siswa-layout title="Dashboard">
    <div class="max-w-4xl mx-auto space-y-6">

        @if (!$kelas)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 text-sm">
                Kamu belum terdaftar di kelas manapun.
            </div>
        @else
            {{-- Status absen hari ini --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs text-slate-400 uppercase tracking-wide mb-1">Status Absen Hari Ini</p>
                @if ($statusAbsenHariIni)
                    <span @class([
                        'inline-block text-sm font-semibold px-3 py-1 rounded-full',
                        'bg-green-100 text-green-800' => $statusAbsenHariIni === 'hadir',
                        'bg-amber-100 text-amber-800' => $statusAbsenHariIni === 'perlu_verifikasi',
                        'bg-blue-100 text-blue-800' => $statusAbsenHariIni === 'izin',
                        'bg-red-100 text-red-800' => $statusAbsenHariIni === 'telat',
                    ])>{{ ucfirst(str_replace('_', ' ', $statusAbsenHariIni)) }}</span>
                @else
                    <a href="{{ route('siswa.absen.create') }}" class="inline-block text-sm font-semibold px-4 py-2 bg-amber-300 text-slate-900 rounded-lg hover:bg-amber-400">
                        Belum Absen — Absen Sekarang
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Jadwal hari ini --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-slate-800">Jadwal Hari Ini</h2>
                        <a href="{{ route('siswa.jadwal.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($jadwalHariIni as $j)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-700">{{ $j->mapel }}</span>
                                <span class="text-slate-400">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
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
                        <a href="{{ route('siswa.kegiatan.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
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
        @endif

    </div>
</x-siswa-layout>