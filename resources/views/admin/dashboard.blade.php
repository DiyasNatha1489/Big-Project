<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Wali Kelas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow rounded">
                <p class="mb-4">Selamat datang, {{ auth()->user()->name }}!</p>

                <a href="{{ route('admin.guru.index') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Guru
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Kelas
                </a>
                <a href="{{ route('admin.rekap.index') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Rekap Presensi
                </a>
                <a href="{{ route('admin.jadwal.index') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Jadwal Pelajaran
                </a>
                <a href="{{ route('admin.kegiatan.index') }}" class="inline-block px-4 py-2 bg-gray-800 text-white rounded">
                    Kegiatan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>