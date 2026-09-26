<x-admin-layout title="Tambah Jadwal">
    <div class="max-w-3xl mx-auto"
         x-data="{
            kelasId: {{ Illuminate\Support\Js::from(old('kelas_id', '')) }},
            days: {{ Illuminate\Support\Js::from(old('days', [['hari' => 'senin', 'blocks' => [['mapel' => '', 'jam_awal' => 1, 'jam_akhir' => 1]]]])) }},
            urutanHari: ['senin','selasa','rabu','kamis','jumat','sabtu'],

            tambahHari() {
                const dipakai = this.days.map(d => d.hari);
                const belumDipakai = this.urutanHari.find(h => !dipakai.includes(h));
                this.days.push({ hari: belumDipakai || 'senin', blocks: [{ mapel: '', jam_awal: 1, jam_akhir: 1 }] });
            },
            hapusHari(i) { this.days.splice(i, 1); },
            tambahBlok(i) {
                const blocks = this.days[i].blocks;
                const terakhir = blocks[blocks.length - 1];
                const jamBerikutnya = terakhir ? Math.min(terakhir.jam_akhir + 1, {{ $jumlahJam }}) : 1;
                blocks.push({ mapel: '', jam_awal: jamBerikutnya, jam_akhir: jamBerikutnya });
            },
            hapusBlok(i, j) { this.days[i].blocks.splice(j, 1); },
            blokBentrok(i, j) {
                const a = this.days[i].blocks[j];
                return this.days[i].blocks.some((b, k) => k !== j && a.jam_awal <= b.jam_akhir && b.jam_awal <= a.jam_akhir);
            },
            adaBentrok() {
                return this.days.some((d, i) => d.blocks.some((_, j) => this.blokBentrok(i, j)));
            },
            hariDobel() {
                const list = this.days.map(d => d.hari);
                return new Set(list).size !== list.length;
            }
         }">

        <div class="bg-white p-6 shadow rounded space-y-5">

            @if (session('error'))
                <div class="p-3 bg-red-100 text-red-800 text-sm rounded">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="p-3 bg-red-100 text-red-800 text-sm rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-5"
                  @submit="if (adaBentrok() || hariDobel()) $event.preventDefault()">
                @csrf

                <div>
                    <x-input-label for="kelas_id" value="Kelas" />
                    <select name="kelas_id" id="kelas_id" x-model="kelasId" class="block w-full mt-1 border-gray-300 rounded" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <p class="text-xs text-slate-400">
                    Sekolah mulai jam {{ config('classly.jam_mulai_sekolah') }}, tiap jam pelajaran {{ config('classly.durasi_per_jam_pelajaran') }} menit. Total {{ $jumlahJam }} jam per hari.
                </p>

                <template x-for="(day, i) in days" :key="i">
                    <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <select :name="'days['+i+'][hari]'" x-model="day.hari" class="font-semibold text-sm border-gray-300 rounded">
                                <option value="senin">Senin</option>
                                <option value="selasa">Selasa</option>
                                <option value="rabu">Rabu</option>
                                <option value="kamis">Kamis</option>
                                <option value="jumat">Jumat</option>
                                <option value="sabtu">Sabtu</option>
                            </select>
                            <button type="button" x-show="days.length > 1" @click="hapusHari(i)" class="text-xs text-red-500 hover:underline">Hapus Hari</button>
                        </div>

                        <p x-show="hariDobel()" x-cloak class="text-xs text-red-600">Hari ini sudah dipakai di baris lain, pilih hari yang berbeda.</p>

                        <template x-for="(block, j) in day.blocks" :key="j">
                            <div class="flex items-start gap-2">
                                <input type="text" :name="'days['+i+'][blocks]['+j+'][mapel]'" x-model="block.mapel"
                                    placeholder="Mata pelajaran" required
                                    class="flex-1 text-sm border-gray-300 rounded"
                                    :class="blokBentrok(i, j) ? 'border-red-400 bg-red-50' : ''">

                                <select :name="'days['+i+'][blocks]['+j+'][jam_awal]'" x-model.number="block.jam_awal"
                                    class="text-sm border-gray-300 rounded" :class="blokBentrok(i, j) ? 'border-red-400 bg-red-50' : ''">
                                    @for ($x = 1; $x <= $jumlahJam; $x++)
                                        <option value="{{ $x }}">Jam-{{ $x }}</option>
                                    @endfor
                                </select>
                                <span class="pt-1.5 text-xs text-slate-400">s/d</span>
                                <select :name="'days['+i+'][blocks]['+j+'][jam_akhir]'" x-model.number="block.jam_akhir"
                                    class="text-sm border-gray-300 rounded" :class="blokBentrok(i, j) ? 'border-red-400 bg-red-50' : ''">
                                    @for ($x = 1; $x <= $jumlahJam; $x++)
                                        <option value="{{ $x }}">Jam-{{ $x }}</option>
                                    @endfor
                                </select>
                                <button type="button" x-show="day.blocks.length > 1" @click="hapusBlok(i, j)" class="text-red-500 text-xs pt-1.5">&times;</button>
                            </div>
                        </template>

                        <p x-show="day.blocks.some((_, j) => blokBentrok(i, j))" x-cloak class="text-xs text-red-600">
                            Ada jam yang tumpang tindih di hari ini, perbaiki dulu sebelum simpan.
                        </p>

                        <button type="button" @click="tambahBlok(i)" class="text-xs text-blue-600 hover:underline">+ Tambah Jam</button>
                    </div>
                </template>

                <button type="button" @click="tambahHari()"
                    class="w-full px-4 py-2 border border-dashed border-gray-300 rounded-lg text-sm text-slate-600 hover:bg-gray-50">
                    + Tambah Hari
                </button>

                <x-primary-button type="submit" class="w-full justify-center"
                    x-bind:disabled="adaBentrok() || hariDobel()"
                    x-bind:class="{ 'opacity-50 cursor-not-allowed': adaBentrok() || hariDobel() }">
                    Simpan Semua Jadwal
                </x-primary-button>
            </form>
        </div>
    </div>
</x-admin-layout>