<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kegiatan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-3">
            @forelse ($kegiatan as $k)
                <div class="bg-white p-4 shadow rounded">
                    <div class="flex justify-between items-start">
                        <h3 class="font-semibold">{{ $k->judul }}</h3>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</span>
                    </div>
                    @if ($k->deskripsi)
                        <p class="text-sm text-gray-600 mt-1">{{ $k->deskripsi }}</p>
                    @endif
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded {{ $k->target === 'semua_kelas' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $k->target === 'semua_kelas' ? 'Semua Kelas' : 'Kelas Kamu' }}
                    </span>
                </div>
            @empty
                <div class="p-4 bg-gray-100 text-gray-500 rounded text-center">Belum ada kegiatan.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>