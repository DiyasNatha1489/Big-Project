<x-wali-kelas-layout title="Kegiatan Kelas {{ $kelas->nama }}">
    <div class="max-w-5xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-6 shadow rounded">
            <h3 class="font-semibold mb-4">Tambah Kegiatan (Piket, Pengumuman, Rapat)</h3>
            <form action="{{ route('wali_kelas.kegiatan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="judul" value="Judul" />
                    <x-text-input id="judul" name="judul" class="block w-full mt-1" :value="old('judul')" required />
                    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="deskripsi" value="Deskripsi" />
                    <textarea id="deskripsi" name="deskripsi" class="block w-full mt-1 border-gray-300 rounded" rows="2">{{ old('deskripsi') }}</textarea>
                </div>
                <div>
                    <x-input-label for="tanggal" value="Tanggal" />
                    <input type="date" id="tanggal" name="tanggal" class="block w-full mt-1 border-gray-300 rounded" value="{{ old('tanggal') }}" required>
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <x-primary-button>Simpan</x-primary-button>
            </form>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan as $k)
                        <tr class="border-t">
                            <td class="p-3">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                            <td class="p-3">{{ $k->judul }}</td>
                            <td class="p-3">
                                <form action="{{ route('wali_kelas.kegiatan.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-3 text-gray-500">Belum ada kegiatan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-wali-kelas-layout>