<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Kegiatan Sekolah</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.kegiatan.store') }}" method="POST" class="bg-white p-6 shadow rounded space-y-4">
                @csrf
                <div>
                    <x-input-label for="judul" value="Judul" />
                    <x-text-input id="judul" name="judul" class="block w-full mt-1" :value="old('judul')" required />
                    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="deskripsi" value="Deskripsi" />
                    <textarea id="deskripsi" name="deskripsi" class="block w-full mt-1 border-gray-300 rounded" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
                <div>
                    <x-input-label for="tanggal" value="Tanggal" />
                    <input type="date" id="tanggal" name="tanggal" class="block w-full mt-1 border-gray-300 rounded" value="{{ old('tanggal') }}" required>
                    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                </div>
                <div>
                    <x-input-label value="Target" />
                    <div class="mt-1 space-y-1">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="target" value="semua_kelas" onchange="document.getElementById('pilih-kelas').classList.add('hidden')" checked>
                            Semua Kelas
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="target" value="kelas_tertentu" onchange="document.getElementById('pilih-kelas').classList.remove('hidden')">
                            Kelas Tertentu
                        </label>
                    </div>
                </div>
                <div id="pilih-kelas" class="hidden">
                    <x-input-label for="kelas_id" value="Pilih Kelas" />
                    <select name="kelas_id" id="kelas_id" class="block w-full mt-1 border-gray-300 rounded">
                        <option value="">-- Pilih --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                </div>
                <x-primary-button>Simpan</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>