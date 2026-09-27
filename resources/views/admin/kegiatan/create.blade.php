<x-admin-layout title="Tambah Kegiatan Sekolah">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
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
            
            <div class="flex gap-3">
                <div class="flex-1">
                    <x-input-label for="jam_mulai" value="Jam Mulai (opsional)" />
                    <input type="time" id="jam_mulai" name="jam_mulai" class="block w-full mt-1 border-gray-300 rounded" value="{{ old('jam_mulai') }}">
                </div>
                <div class="flex-1">
                    <x-input-label for="jam_selesai" value="Jam Selesai (opsional)" />
                    <input type="time" id="jam_selesai" name="jam_selesai" class="block w-full mt-1 border-gray-300 rounded" value="{{ old('jam_selesai') }}">
                    <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="lokasi" value="Lokasi (opsional)" />
                <x-text-input id="lokasi" name="lokasi" class="block w-full mt-1" :value="old('lokasi')" placeholder="Aula sekolah, Lapangan, dst" />
            </div>

            <div>
                <x-input-label for="dresscode" value="Dresscode (opsional)" />
                <x-text-input id="dresscode" name="dresscode" class="block w-full mt-1" :value="old('dresscode')" placeholder="Seragam batik, bebas rapi, dst" />
            </div>

            <div>
                <x-input-label for="peraturan" value="Peraturan / Catatan Tambahan (opsional)" />
                <textarea id="peraturan" name="peraturan" rows="3" class="block w-full mt-1 border-gray-300 rounded" placeholder="Wajib bawa alat tulis, dilarang membawa HP, dst">{{ old('peraturan') }}</textarea>
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
</x-admin-layout>