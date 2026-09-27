<x-wali-kelas-layout title="Kegiatan Kelas {{ $kelas->nama }}">
    <div class="max-w-3xl mx-auto space-y-6"
         x-data="{
            modalOpen: false,
            editId: null,
            editJudul: '',
            editDeskripsi: '',
            editTanggal: '',
            editJamMulai: '',
            editJamSelesai: '',
            editLokasi: '',
            editDresscode: '',
            editPeraturan: '',
            bukaEdit(k) {
                this.editId = k.id;
                this.editJudul = k.judul;
                this.editDeskripsi = k.deskripsi ?? '';
                this.editTanggal = k.tanggal;
                this.editJamMulai = k.jam_mulai ?? '';
                this.editJamSelesai = k.jam_selesai ?? '';
                this.editLokasi = k.lokasi ?? '';
                this.editDresscode = k.dresscode ?? '';
                this.editPeraturan = k.peraturan ?? '';
                this.modalOpen = true;
            }
         }">

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
                    <x-text-input id="lokasi" name="lokasi" class="block w-full mt-1" :value="old('lokasi')" />
                </div>
                <div>
                    <x-input-label for="dresscode" value="Dresscode (opsional)" />
                    <x-text-input id="dresscode" name="dresscode" class="block w-full mt-1" :value="old('dresscode')" />
                </div>
                <div>
                    <x-input-label for="peraturan" value="Peraturan / Catatan (opsional)" />
                    <textarea id="peraturan" name="peraturan" rows="2" class="block w-full mt-1 border-gray-300 rounded">{{ old('peraturan') }}</textarea>
                </div>
                <x-primary-button>Simpan</x-primary-button>
            </form>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3">Jam</th>
                            <th class="p-3">Lokasi</th>
                            <th class="p-3">Sumber</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $k)
                            <tr class="border-t">
                                <td class="p-3">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                                <td class="p-3">{{ $k->judul }}</td>
                                <td class="p-3 text-slate-500">
                                    @if ($k->jam_mulai)
                                        {{ substr($k->jam_mulai, 0, 5) }}@if($k->jam_selesai) - {{ substr($k->jam_selesai, 0, 5) }}@endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-3 text-slate-500">{{ $k->lokasi ?? '-' }}</td>
                                <td class="p-3">
                                    <span class="text-xs px-2 py-0.5 rounded {{ $k->dibuat_oleh_role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $k->dibuat_oleh_role === 'admin' ? 'Sekolah' : 'Kelas' }}
                                    </span>
                                </td>
                                <td class="p-3 space-x-2">
                                    @if ($k->dibuat_oleh_role === 'wali_kelas')
                                        <button type="button"
                                            @click="bukaEdit({{ Illuminate\Support\Js::from($k->only(['id','judul','deskripsi','tanggal','jam_mulai','jam_selesai','lokasi','dresscode','peraturan'])) }})"
                                            class="text-blue-600 hover:underline">Edit</button>
                                        <form action="{{ route('wali_kelas.kegiatan.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @else
                                        <span class="text-slate-300 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-3 text-gray-500">Belum ada kegiatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 max-h-[85vh] overflow-y-auto" @click.away="modalOpen = false">
                <h3 class="font-semibold text-slate-800 mb-4">Edit Kegiatan</h3>

                <form :action="'/wali-kelas/kegiatan/' + editId" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="judul" x-model="editJudul" required class="block w-full mt-1 border-gray-300 rounded">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" x-model="editDeskripsi" rows="2" class="block w-full mt-1 border-gray-300 rounded"></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal" x-model="editTanggal" required class="block w-full mt-1 border-gray-300 rounded">
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-700">Jam Mulai</label>
                            <input type="time" name="jam_mulai" x-model="editJamMulai" class="block w-full mt-1 border-gray-300 rounded">
                        </div>
                        <div class="flex-1">
                            <label class="text-sm font-medium text-gray-700">Jam Selesai</label>
                            <input type="time" name="jam_selesai" x-model="editJamSelesai" class="block w-full mt-1 border-gray-300 rounded">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="lokasi" x-model="editLokasi" class="block w-full mt-1 border-gray-300 rounded">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Dresscode</label>
                        <input type="text" name="dresscode" x-model="editDresscode" class="block w-full mt-1 border-gray-300 rounded">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Peraturan / Catatan</label>
                        <textarea name="peraturan" x-model="editPeraturan" rows="2" class="block w-full mt-1 border-gray-300 rounded"></textarea>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="modalOpen = false" class="flex-1 px-4 py-2 bg-gray-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-wali-kelas-layout>