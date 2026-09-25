<x-admin-layout title="Kelola Kelas">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.kelas.create') }}" class="inline-block mb-4 px-4 py-2 bg-gray-800 text-white rounded">
            + Tambah Kelas
        </a>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nama Kelas</th>
                        <th class="p-3">Kode Unik</th>
                        <th class="p-3">Wali Kelas</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelas as $k)
                        <tr class="border-t">
                            <td class="p-3">{{ $k->nama }}</td>
                            <td class="p-3 font-mono">{{ $k->kode_unik }}</td>
                            <td class="p-3">{{ $k->waliKelas->name ?? '-' }}</td>
                            <td class="p-3 space-x-2">
                                <a href="{{ route('admin.kelas.edit', $k) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-3 text-gray-500">Belum ada kelas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>