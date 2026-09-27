<x-siswa-layout title="Kegiatan">
    <div class="max-w-4xl mx-auto space-y-6">
        @forelse ($kegiatan as $k)
        <div class="bg-white p-4 shadow rounded space-y-2">
            <div class="flex justify-between items-start">
                <h3 class="font-semibold">{{ $k->judul }}</h3>
                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</span>
            </div>

            @if ($k->deskripsi)
                <p class="text-sm text-gray-600">{{ $k->deskripsi }}</p>
            @endif

            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                @if ($k->jam_mulai)
                    <span>🕒 {{ substr($k->jam_mulai, 0, 5) }}@if($k->jam_selesai) - {{ substr($k->jam_selesai, 0, 5) }}@endif</span>
                @endif
                @if ($k->lokasi)
                    <span>📍 {{ $k->lokasi }}</span>
                @endif
                @if ($k->dresscode)
                    <span>👕 {{ $k->dresscode }}</span>
                @endif
            </div>

            @if ($k->peraturan)
                <p class="text-xs text-amber-700 bg-amber-50 rounded p-2">{{ $k->peraturan }}</p>
            @endif

            <span class="inline-block text-xs px-2 py-0.5 rounded {{ $k->target === 'semua_kelas' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $k->target === 'semua_kelas' ? 'Semua Kelas' : 'Kelas Kamu' }}
            </span>
        </div>
    @empty
        <div class="p-4 bg-gray-100 text-gray-500 rounded text-center">Belum ada kegiatan.</div>
    @endforelse
    </div>
</x-siswa-layout>