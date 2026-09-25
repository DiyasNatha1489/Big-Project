<x-admin-layout title="Rekap Presensi Semua Kelas">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
        <form method="GET" class="flex items-center gap-2">
            <label for="tanggal" class="text-sm">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                class="border-gray-300 rounded" onchange="this.form.submit()">
        </form>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Wali Kelas</th>
                        <th class="p-3">Hadir</th>
                        <th class="p-3">Telat</th>
                        <th class="p-3">Izin</th>
                        <th class="p-3">Sakit</th>
                        <th class="p-3">Perlu Verifikasi</th>
                        <th class="p-3">Alpa</th>
                        <th class="p-3">Total Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $r)
                        <tr class="border-t {{ !$r['sesi_dibuka'] ? 'bg-gray-50 text-gray-400' : '' }}">
                            <td class="p-3">{{ $r['kelas']->nama }}</td>
                            <td class="p-3">{{ $r['kelas']->waliKelas->name ?? '-' }}</td>
                            <td class="p-3">{{ $r['hadir'] }}</td>
                            <td class="p-3">{{ $r['telat'] }}</td>
                            <td class="p-3">{{ $r['izin'] }}</td>
                            <td class="p-3">{{ $r['sakit'] }}</td>
                            <td class="p-3">{{ $r['perlu_verifikasi'] }}</td>
                            <td class="p-3">{{ $r['alpa'] }}</td>
                            <td class="p-3">{{ $r['total_siswa'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-500">Baris abu-abu menandakan sesi presensi belum dibuka untuk tanggal tersebut (belum ada siswa yang membuka halaman absen hari itu).</p>
    </div>
</x-admin-layout>