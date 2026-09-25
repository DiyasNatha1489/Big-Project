<x-admin-layout title="Kelola Jadwal">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center">
            <form method="GET">
                <select name="kelas_id" onchange="this.form.submit()" class="border-gray-300 rounded">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected($kelasId == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.jadwal.create') }}" class="px-4 py-2 bg-gray-800 text-white rounded">+ Tambah Jadwal</a>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Hari</th>
                        <th class="p-3">Mapel</th>
                        <th class="p-3">Jam</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwal as $j)
                        <tr class="border-t">
                            <td class="p-3">{{ $j->kelas->nama }}</td>
                            <td class="p-3">{{ ucfirst($j->hari) }}</td>
                            <td class="p-3">{{ $j->mapel }}</td>
                            <td class="p-3">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                            <td class="p-3">
                                <form action="{{ route('admin.jadwal.destroy', $j) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-3 text-gray-500">Belum ada jadwal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>