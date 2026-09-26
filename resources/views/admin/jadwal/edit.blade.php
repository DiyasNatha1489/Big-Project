<x-admin-layout title="Edit Jadwal">
    <div class="max-w-md mx-auto">
        <form action="{{ route('admin.jadwal.update', $jadwal) }}" method="POST" class="bg-white p-6 shadow rounded space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="kelas_id" value="Kelas" />
                <select name="kelas_id" id="kelas_id" class="block w-full mt-1 border-gray-300 rounded" required>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected(old('kelas_id', $jadwal->kelas_id) == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="hari" value="Hari" />
                <select name="hari" id="hari" class="block w-full mt-1 border-gray-300 rounded" required>
                    @foreach (['senin','selasa','rabu','kamis','jumat','sabtu'] as $h)
                        <option value="{{ $h }}" @selected(old('hari', $jadwal->hari) === $h)>{{ ucfirst($h) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="mapel" value="Mata Pelajaran" />
                <x-text-input id="mapel" name="mapel" class="block w-full mt-1" :value="old('mapel', $jadwal->mapel)" required />
                <x-input-error :messages="$errors->get('mapel')" class="mt-2" />
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <x-input-label for="jam_awal" value="Dari Jam Ke" />
                    <select name="jam_awal" id="jam_awal" class="block w-full mt-1 border-gray-300 rounded">
                        @for ($i = 1; $i <= $jumlahJam; $i++)
                            <option value="{{ $i }}" @selected(old('jam_awal', $jamAwal) == $i)>Jam ke-{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex-1">
                    <x-input-label for="jam_akhir" value="Sampai Jam Ke" />
                    <select name="jam_akhir" id="jam_akhir" class="block w-full mt-1 border-gray-300 rounded">
                        @for ($i = 1; $i <= $jumlahJam; $i++)
                            <option value="{{ $i }}" @selected(old('jam_akhir', $jamAkhir) == $i)>Jam ke-{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <x-input-error :messages="$errors->get('jam_akhir')" class="mt-2" />

            <x-primary-button class="w-full justify-center">Simpan Perubahan</x-primary-button>
        </form>
    </div>
</x-admin-layout>