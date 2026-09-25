<x-admin-layout title="Tambah Kelas">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="bg-white p-6 shadow rounded space-y-4">
            @csrf
            <div>
                <x-input-label for="nama" value="Nama Kelas" />
                <x-text-input id="nama" name="nama" class="block w-full mt-1" :value="old('nama')" required />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="wali_kelas_id" value="Wali Kelas" />
                <select id="wali_kelas_id" name="wali_kelas_id" class="block w-full mt-1 border-gray-300 rounded">
                    <option value="">-- Belum ditentukan --</option>
                    @foreach ($guru as $g)
                        <option value="{{ $g->id }}" @selected(old('wali_kelas_id') == $g->id)>{{ $g->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('wali_kelas_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="latitude" value="Latitude Lokasi Kelas" />
                <x-text-input id="latitude" name="latitude" type="text" class="block w-full mt-1" :value="old('latitude')" placeholder="-7.3612345" required />
                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="longitude" value="Longitude Lokasi Kelas" />
                <x-text-input id="longitude" name="longitude" type="text" class="block w-full mt-1" :value="old('longitude')" placeholder="109.9012345" required />
                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
            </div>
            <x-primary-button>Simpan</x-primary-button>
        </form>
    </div>
</x-admin-layout>