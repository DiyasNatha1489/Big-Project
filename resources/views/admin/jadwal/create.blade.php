<x-admin-layout title="Tambah Jadwal">
    <div class="max-w-4xl mx-auto">

    <div class="max-w-4xl mx-auto space-y-4">
        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="bg-white p-6 shadow rounded space-y-4">
            @csrf
            <div>
                <x-input-label for="kelas_id" value="Kelas" />
                <select name="kelas_id" id="kelas_id" class="block w-full mt-1 border-gray-300 rounded" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="hari" value="Hari" />
                <select name="hari" id="hari" class="block w-full mt-1 border-gray-300 rounded" required>
                    @foreach (['senin','selasa','rabu','kamis','jumat','sabtu'] as $h)
                        <option value="{{ $h }}">{{ ucfirst($h) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="mapel" value="Mata Pelajaran" />
                <x-text-input id="mapel" name="mapel" class="block w-full mt-1" :value="old('mapel')" required />
            </div>
            <div class="flex gap-4">
                <div class="flex-1">
                    <x-input-label for="jam_mulai" value="Jam Mulai" />
                    <input type="time" name="jam_mulai" id="jam_mulai" class="block w-full mt-1 border-gray-300 rounded" required>
                </div>
                <div class="flex-1">
                    <x-input-label for="jam_selesai" value="Jam Selesai" />
                    <input type="time" name="jam_selesai" id="jam_selesai" class="block w-full mt-1 border-gray-300 rounded" required>
                </div>
            </div>
            <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
            <x-primary-button>Simpan</x-primary-button>
        </form>
    </div>
</x-admin-layout>