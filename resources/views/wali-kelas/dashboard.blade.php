<x-wali-kelas-layout title="Dashboard {{ $kelas->nama }}">
    <div class="max-w-5xl mx-auto space-y-6">

        @if (!$kelas)
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 text-sm">
                Kamu belum ditugaskan sebagai wali kelas manapun. Hubungi Admin.
            </div>
        @else
            <p class="text-slate-600">Kelas yang kamu ampu: <span class="font-semibold text-slate-800">{{ $kelas->nama }}</span></p>

            {{-- Kartu statistik --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wide">Total Siswa</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wide">Hadir Hari Ini</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800">{{ $hadirHariIni }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wide">Perlu Verifikasi</p>
                    <p class="mt-1 text-2xl font-bold {{ $perluVerifikasiHariIni > 0 ? 'text-amber-600' : 'text-slate-800' }}">{{ $perluVerifikasiHariIni }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <p class="text-xs text-slate-400 uppercase tracking-wide">Alpa Hari Ini</p>
                    <p class="mt-1 text-2xl font-bold text-slate-800">{{ $alpaHariIni }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Presensi terbaru --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-slate-800">Presensi Hari Ini</h2>
                        <a href="{{ route('wali_kelas.presensi.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
                    </div>
                    <div class="space-y-3">
                        @forelse ($presensiTerbaru as $p)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-700">{{ $p->siswa->name }}</span>
                                <span @class([
                                    'text-xs px-2 py-0.5 rounded-full',
                                    'bg-green-100 text-green-800' => $p->status === 'hadir',
                                    'bg-amber-100 text-amber-800' => $p->status === 'perlu_verifikasi',
                                    'bg-blue-100 text-blue-800' => $p->status === 'izin',
                                    'bg-red-100 text-red-800' => $p->status === 'telat',
                                ])>{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Belum ada yang absen hari ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Kegiatan terdekat --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-semibold text-slate-800">Kegiatan Terdekat</h2>
                        <a href="{{ route('wali_kelas.kegiatan.index') }}" class="text-xs text-amber-600 font-medium hover:underline">Lihat Semua</a>
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
</x-wali-kelas-layout>