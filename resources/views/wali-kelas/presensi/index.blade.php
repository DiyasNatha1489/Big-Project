<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Presensi Kelas {{ $kelas->nama }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <form method="GET" class="flex items-center gap-2">
                <label for="tanggal" class="text-sm">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                    class="border-gray-300 rounded" onchange="this.form.submit()">
            </form>

            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Foto</th>
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3">Jam</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($presensi as $p)
                            <tr class="border-t">
                                <td class="p-3">
                                    <a href="{{ asset('storage/'.$p->foto) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$p->foto) }}" class="w-14 h-14 object-cover rounded">
                                    </a>
                                </td>
                                <td class="p-3">{{ $p->siswa->name }}</td>
                                <td class="p-3">{{ $p->created_at->format('H:i') }}</td>
                                <td class="p-3">
                                    <span @class([
                                        'px-2 py-1 rounded text-xs',
                                        'bg-green-100 text-green-800' => $p->status === 'hadir',
                                        'bg-yellow-100 text-yellow-800' => $p->status === 'perlu_verifikasi',
                                        'bg-blue-100 text-blue-800' => $p->status === 'izin',
                                        'bg-red-100 text-red-800' => $p->status === 'telat',
                                    ])>
                                        {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <form action="{{ route('wali_kelas.presensi.verifikasi', $p) }}" method="POST" class="flex gap-2">
                                        @csrf @method('PATCH')
                                        <select name="status" class="text-xs border-gray-300 rounded">
                                            <option value="hadir" @selected($p->status === 'hadir')>Hadir</option>
                                            <option value="telat" @selected($p->status === 'telat')>Telat</option>
                                            <option value="izin" @selected($p->status === 'izin')>Izin</option>
                                            <option value="perlu_verifikasi" @selected($p->status === 'perlu_verifikasi')>Perlu Verifikasi</option>
                                        </select>
                                        <button type="submit" class="text-xs px-2 py-1 bg-gray-800 text-white rounded">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-3 text-gray-500">Belum ada yang absen tanggal ini.</td></tr>
                        @endforelse

                        @foreach ($siswaBelumAbsen as $s)
                            <tr class="border-t bg-red-50">
                                <td class="p-3">-</td>
                                <td class="p-3">{{ $s->name }}</td>
                                <td class="p-3">-</td>
                                <td class="p-3"><span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">Alpa</span></td>
                                <td class="p-3 text-gray-400">-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>