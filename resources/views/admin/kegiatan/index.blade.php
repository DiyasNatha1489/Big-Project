<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Kegiatan Sekolah</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <a href="{{ route('admin.kegiatan.create') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                + Tambah Kegiatan
            </a>

            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Target</th>
                            <th class="p-3">Dibuat Oleh</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $k)
                            <tr class="border-t">
                                <td class="p-3">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                                <td class="p-3">{{ $k->judul }}</td>
                                <td class="p-3">
                                    {{ $k->target === 'semua_kelas' ? 'Semua Kelas' : ($k->kelas->nama ?? '-') }}
                                </td>
                                <td class="p-3">{{ $k->dibuat_oleh_role === 'admin' ? 'Admin' : 'Wali Kelas' }}</td>
                                <td class="p-3">
                                    <form action="{{ route('admin.kegiatan.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus kegiatan ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-gray-500">Belum ada kegiatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>