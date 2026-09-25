<x-siswa-layout title="Jadwal Kelas {{ $kelas->nama }}">
    <div class="max-w-4xl mx-auto space-y-6">

        @if (session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        @forelse (['senin','selasa','rabu','kamis','jumat','sabtu'] as $hari)
            @if (isset($jadwal[$hari]))
                <div class="bg-white shadow rounded overflow-hidden">
                    <div class="bg-gray-800 text-white px-4 py-2 font-semibold">{{ ucfirst($hari) }}</div>
                    <table class="w-full text-left text-sm">
                        <tbody>
                            @foreach ($jadwal[$hari] as $j)
                                <tr class="border-t">
                                    <td class="p-3">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                    <td class="p-3">{{ $j->mapel }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
        @endforelse

        @if ($jadwal->isEmpty())
            <div class="p-4 bg-gray-100 text-gray-500 rounded text-center">
                Jadwal untuk kelasmu belum diatur oleh Admin.
            </div>
        @endif
    </div>
</x-siswa-layout>