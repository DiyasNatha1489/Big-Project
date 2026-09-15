<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Jadwal Pelajaran — {{ $kelas->nama }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

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
    </div>
</x-app-layout>