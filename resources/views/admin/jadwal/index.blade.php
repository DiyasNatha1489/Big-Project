<x-admin-layout title="Kelola Jadwal">
    <div class="max-w-5xl mx-auto space-y-4"
         x-data="{
            modalBulkOpen: false,
            rowsEdit: [],
            bukaEditMassal() {
                const checked = document.querySelectorAll('input[name=\'ids[]\']:checked');
                if (checked.length === 0) return;
                this.rowsEdit = Array.from(checked).map(el => ({
                    id: el.dataset.id,
                    hari: el.dataset.hari,
                    mapel: el.dataset.mapel,
                    jam_awal: parseInt(el.dataset.jamAwal),
                    jam_akhir: parseInt(el.dataset.jamAkhir),
                }));
                this.modalBulkOpen = true;
            }
         }">

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center">
            <form method="GET">
                <select name="kelas_id" onchange="this.form.submit()" class="border-gray-300 rounded">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" @selected($kelasId == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.jadwal.create') }}" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">+ Tambah Jadwal</a>
        </div>

        <form id="form-bulk" action="{{ route('admin.jadwal.bulk-destroy') }}" method="POST"
              onsubmit="return confirm('Hapus semua jadwal yang dicentang?')">
            @csrf
            @method('DELETE')

            @forelse ($jadwalPerKelas as $kelasIdGroup => $perHari)
                @php $namaKelas = $perHari->first()->first()->kelas->nama; @endphp
                <div class="bg-white shadow rounded overflow-hidden mb-4">
                    <div class="bg-slate-800 text-white px-4 py-2 flex items-center justify-between">
                        <span class="font-semibold text-sm">{{ $namaKelas }}</span>
                        <label class="flex items-center gap-2 text-xs cursor-pointer">
                            <input type="checkbox" onclick="toggleGroup(this, 'kelas-{{ $kelasIdGroup }}')">
                            Pilih Semua Kelas Ini
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-gray-200">
                        @foreach ($perHari as $hari => $items)
                            <div class="bg-white p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-xs font-semibold text-slate-500 uppercase">{{ $hari }}</p>
                                    <label class="flex items-center gap-1 text-[10px] text-slate-400 cursor-pointer">
                                        <input type="checkbox" onclick="toggleGroup(this, 'kelas-{{ $kelasIdGroup }}-{{ $hari }}')">
                                        semua
                                    </label>
                                </div>
                                <table class="w-full text-xs">
                                    <tbody>
                                        @foreach ($items as $j)
                                            <tr class="border-t border-gray-100">
                                                <td class="py-1.5 w-6">
                                                    <input type="checkbox" name="ids[]" value="{{ $j->id }}"
                                                        data-id="{{ $j->id }}"
                                                        data-hari="{{ $j->hari }}"
                                                        data-mapel="{{ $j->mapel }}"
                                                        data-jam-awal="{{ $j->jam_ke_awal }}"
                                                        data-jam-akhir="{{ $j->jam_ke_akhir }}"
                                                        class="kelas-{{ $kelasIdGroup }} kelas-{{ $kelasIdGroup }}-{{ $hari }}">
                                                </td>
                                                <td class="py-1.5 w-20 text-slate-400">
                                                    Jam {{ $j->jam_ke_awal }}@if($j->jam_ke_akhir > $j->jam_ke_awal)-{{ $j->jam_ke_akhir }}@endif
                                                </td>
                                                <td class="py-1.5 text-slate-700">{{ $j->mapel }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded p-6 text-center text-slate-400 text-sm">Belum ada jadwal.</div>
            @endforelse

            @if ($jadwalPerKelas->isNotEmpty())
                <div class="flex gap-2">
                    <button type="button" @click="bukaEditMassal()"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        Edit yang Dicentang
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                        Hapus yang Dicentang
                    </button>
                </div>
            @endif
        </form>

        {{-- MODAL: Edit massal (juga dipakai untuk edit 1 baris) --}}
        <div x-show="modalBulkOpen" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 max-h-[85vh] overflow-y-auto" @click.away="modalBulkOpen = false">
                <h3 class="font-semibold text-slate-800 mb-4">Edit <span x-text="rowsEdit.length"></span> Jadwal Terpilih</h3>

                <form action="{{ route('admin.jadwal.bulk-update') }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')

                    <template x-for="(row, index) in rowsEdit" :key="row.id">
                        <div class="border border-gray-200 rounded-lg p-3 space-y-2">
                            <input type="hidden" :name="'rows['+index+'][id]'" :value="row.id">

                            <div class="flex gap-2">
                                <select :name="'rows['+index+'][hari]'" x-model="row.hari" class="flex-1 text-sm border-gray-300 rounded">
                                    <option value="senin">Senin</option>
                                    <option value="selasa">Selasa</option>
                                    <option value="rabu">Rabu</option>
                                    <option value="kamis">Kamis</option>
                                    <option value="jumat">Jumat</option>
                                    <option value="sabtu">Sabtu</option>
                                </select>
                                <input type="text" :name="'rows['+index+'][mapel]'" x-model="row.mapel" class="flex-1 text-sm border-gray-300 rounded" placeholder="Mata pelajaran">
                            </div>

                            <div class="flex gap-2">
                                <select :name="'rows['+index+'][jam_awal]'" x-model.number="row.jam_awal" class="flex-1 text-sm border-gray-300 rounded">
                                    @for ($i = 1; $i <= $jumlahJam; $i++)
                                        <option value="{{ $i }}">Dari Jam-{{ $i }}</option>
                                    @endfor
                                </select>
                                <select :name="'rows['+index+'][jam_akhir]'" x-model.number="row.jam_akhir" class="flex-1 text-sm border-gray-300 rounded">
                                    @for ($i = 1; $i <= $jumlahJam; $i++)
                                        <option value="{{ $i }}">Sampai Jam-{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </template>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="modalBulkOpen = false" class="flex-1 px-4 py-2 bg-gray-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700">Simpan Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleGroup(source, className) {
            document.querySelectorAll('.' + className).forEach(function (el) {
                el.checked = source.checked;
            });
        }
    </script>
</x-admin-layout>