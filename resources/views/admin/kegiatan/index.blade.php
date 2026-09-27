<x-admin-layout title="Kelola Kegiatan Sekolah">
    <div class="max-w-4xl mx-auto space-y-4"
         x-data="{
            modalOpen: false,
            editId: null,
            editJudul: '', editDeskripsi: '', editTanggal: '',
            editJamMulai: '', editJamSelesai: '',
            editLokasi: '', editDresscode: '', editPeraturan: '',
            editTarget: 'semua_kelas', editKelasId: '',
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
                this.editTarget = k.target;
                this.editKelasId = k.kelas_id ?? '';
                this.modalOpen = true;
            }
         }">

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.kegiatan.create') }}" class="inline-block px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">
            + Tambah Kegiatan
        </a>

        <div class="bg-white shadow rounded overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 whitespace-nowrap">Tanggal</th>
                            <th class="p-3">Judul</th>
                            <th class="p-3 whitespace-nowrap">Jam</th>
                            <th class="p-3">Lokasi</th>
                            <th class="p-3">Target</th>
                            <th class="p-3 whitespace-nowrap">Dibuat Oleh</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kegiatan as $k)
                            <tr class="border-t">
                                <td class="p-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                                <td class="p-3">{{ $k->judul }}</td>
                                <td class="p-3 text-slate-500 whitespace-nowrap">
                                    @if ($k->jam_mulai)
                                        {{ substr($k->jam_mulai, 0, 5) }}@if($k->jam_selesai) - {{ substr($k->jam_selesai, 0, 5) }}@endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-3 text-slate-500">{{ $k->lokasi ?? '-' }}</td>
                                <td class="p-3 whitespace-nowrap">
                                    {{ $k->target === 'semua_kelas' ? 'Semua Kelas' : ($k->kelas->nama ?? '-') }}
                                </td>
                                <td class="p-3 whitespace-nowrap">{{ $k->dibuat_oleh_role === 'admin' ? 'Admin' : 'Wali Kelas' }}</td>
                                <td class="p-3 space-x-2 whitespace-nowrap">
                                    @if ($k->dibuat_oleh_role === 'admin')
                                        <button type="button"
                                            @click="bukaEdit({{ Illuminate\Support\Js::from($k->only(['id','judul','deskripsi','tanggal','jam_mulai','jam_selesai','lokasi','dresscode','peraturan','target','kelas_id'])) }})"
                                            class="text-blue-600 hover:underline">Edit</button>
                                        <form action="{{ route('admin.kegiatan.destroy', $k) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kegiatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    @else
                                        <span class="text-slate-300 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="p-3 text-gray-500">Belum ada kegiatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="modalOpen" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 max-h-[85vh] overflow-y-auto" @click.away="modalOpen = false">
                <h3 class="font-semibold text-slate-800 mb-4">Edit Kegiatan</h3>

                <form :action="'/admin/kegiatan/' + editId" method="POST" class="space-y-3">
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
                    <div class="flex flex-col sm:flex-row gap-3">
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

                    <div>
                        <label class="text-sm font-medium text-gray-700">Target</label>
                        <div class="mt-1 space-y-1">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="target" value="semua_kelas" x-model="editTarget"> Semua Kelas
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="target" value="kelas_tertentu" x-model="editTarget"> Kelas Tertentu
                            </label>
                        </div>
                    </div>
                    <div x-show="editTarget === 'kelas_tertentu'" x-cloak>
                        <label class="text-sm font-medium text-gray-700">Pilih Kelas</label>
                        <select name="kelas_id" x-model="editKelasId" class="block w-full mt-1 border-gray-300 rounded">
                            <option value="">-- Pilih --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                        <button type="button" @click="modalOpen = false" class="flex-1 px-4 py-2 bg-gray-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>