<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    private array $urutanHari = ['senin','selasa','rabu','kamis','jumat','sabtu'];

    public function index(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $kelas = Kelas::all();
        $jumlahJam = config('classly.jumlah_jam_per_hari');

        $jadwal = Jadwal::with('kelas')
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->get()
            ->map(function ($j) {
                $j->jam_ke_awal = $this->jamKeDari($j->jam_mulai);
                $j->jam_ke_akhir = $this->jamKeDari($j->jam_selesai) - 1;
                return $j;
            });

        $jadwalPerKelas = $jadwal->groupBy(fn ($j) => $j->kelas_id)
            ->map(function ($items) {
                return $items->groupBy('hari')
                    ->sortBy(fn ($_, $hari) => array_search($hari, $this->urutanHari))
                    ->map(fn ($items) => $items->sortBy('jam_mulai'));
            });

        return view('admin.jadwal.index', compact('jadwalPerKelas', 'kelas', 'kelasId', 'jumlahJam'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $jumlahJam = config('classly.jumlah_jam_per_hari');
        return view('admin.jadwal.create', compact('kelas', 'jumlahJam'));
    }

    public function store(Request $request)
    {
        $jumlahJam = config('classly.jumlah_jam_per_hari');

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'days' => 'required|array|min:1',
            'days.*.hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'days.*.blocks' => 'required|array|min:1',
            'days.*.blocks.*.mapel' => 'required|string|max:100',
            'days.*.blocks.*.jam_awal' => "required|integer|min:1|max:$jumlahJam",
            'days.*.blocks.*.jam_akhir' => "required|integer|min:1|max:$jumlahJam|gte:days.*.blocks.*.jam_awal",
        ]);

        // Cegah hari yang sama dipilih dua kali dalam satu submit
        $hariList = collect($request->days)->pluck('hari');
        if ($hariList->count() !== $hariList->unique()->count()) {
            return back()->withInput()->with('error', 'Ada hari yang dipilih lebih dari sekali dalam form ini.');
        }

        // Cek tumpang tindih jam: sesama blok baru, dan terhadap jadwal yang sudah tersimpan
        foreach ($request->days as $day) {
            $hari = $day['hari'];
            $blokBaru = collect($day['blocks'])->map(fn ($b) => [
                'awal' => (int) $b['jam_awal'],
                'akhir' => (int) $b['jam_akhir'],
                'mapel' => $b['mapel'],
            ]);

            foreach ($blokBaru as $i => $a) {
                foreach ($blokBaru as $j => $b) {
                    if ($i < $j && $a['awal'] <= $b['akhir'] && $b['awal'] <= $a['akhir']) {
                        return back()->withInput()->with('error',
                            "Jam pada hari ".ucfirst($hari)." saling tumpang tindih ({$a['mapel']} dan {$b['mapel']}).");
                    }
                }
            }

            $existing = Jadwal::where('kelas_id', $request->kelas_id)->where('hari', $hari)->get();
            foreach ($existing as $e) {
                $awalLama = $this->jamKeDari($e->jam_mulai);
                $akhirLama = $this->jamKeDari($e->jam_selesai) - 1;

                foreach ($blokBaru as $b) {
                    if ($b['awal'] <= $akhirLama && $awalLama <= $b['akhir']) {
                        return back()->withInput()->with('error',
                            "Jam {$b['awal']}-{$b['akhir']} pada hari ".ucfirst($hari)." bentrok dengan jadwal '{$e->mapel}' yang sudah ada.");
                    }
                }
            }
        }

        $jumlahDitambahkan = 0;
        foreach ($request->days as $day) {
            foreach ($day['blocks'] as $block) {
                Jadwal::create([
                    'kelas_id' => $request->kelas_id,
                    'hari' => $day['hari'],
                    'mapel' => $block['mapel'],
                    'jam_mulai' => $this->waktuJamKe($block['jam_awal']),
                    'jam_selesai' => $this->waktuJamKe($block['jam_akhir'] + 1),
                    'dibuat_oleh' => auth()->id(),
                ]);
                $jumlahDitambahkan++;
            }
        }

        return redirect()->route('admin.jadwal.index')->with('success', "$jumlahDitambahkan jadwal berhasil ditambahkan.");
    }

    public function edit(Jadwal $jadwal)
    {
        $kelas = Kelas::all();
        $jumlahJam = config('classly.jumlah_jam_per_hari');

        $jamAwal = $this->jamKeDari($jadwal->jam_mulai);
        $jamAkhir = $this->jamKeDari($jadwal->jam_selesai) - 1;

        return view('admin.jadwal.edit', compact('jadwal', 'kelas', 'jumlahJam', 'jamAwal', 'jamAkhir'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $jumlahJam = config('classly.jumlah_jam_per_hari');

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'mapel' => 'required|string|max:100',
            'jam_awal' => "required|integer|min:1|max:$jumlahJam",
            'jam_akhir' => "required|integer|min:1|max:$jumlahJam|gte:jam_awal",
        ]);

        $jadwal->update([
            'kelas_id' => $request->kelas_id,
            'hari' => $request->hari,
            'mapel' => $request->mapel,
            'jam_mulai' => $this->waktuJamKe($request->jam_awal),
            'jam_selesai' => $this->waktuJamKe($request->jam_akhir + 1),
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function bulkUpdate(Request $request)
    {
        $jumlahJam = config('classly.jumlah_jam_per_hari');

        $request->validate([
            'rows' => 'required|array|min:1',
            'rows.*.id' => 'required|exists:jadwal,id',
            'rows.*.hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'rows.*.mapel' => 'required|string|max:100',
            'rows.*.jam_awal' => "required|integer|min:1|max:$jumlahJam",
            'rows.*.jam_akhir' => "required|integer|min:1|max:$jumlahJam|gte:rows.*.jam_awal",
        ]);

        foreach ($request->rows as $row) {
            Jadwal::where('id', $row['id'])->update([
                'hari' => $row['hari'],
                'mapel' => $row['mapel'],
                'jam_mulai' => $this->waktuJamKe($row['jam_awal']),
                'jam_selesai' => $this->waktuJamKe($row['jam_akhir'] + 1),
            ]);
        }

        return back()->with('success', count($request->rows).' jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:jadwal,id',
        ]);

        $jumlah = Jadwal::whereIn('id', $request->ids)->delete();

        return back()->with('success', "$jumlah jadwal berhasil dihapus.");
    }

    private function waktuJamKe(int $jamKe): string
    {
        $durasi = config('classly.durasi_per_jam_pelajaran');
        $mulai = Carbon::parse(config('classly.jam_mulai_sekolah'));

        return $mulai->addMinutes(($jamKe - 1) * $durasi)->format('H:i');
    }

    private function jamKeDari(string $jam): int
    {
        $durasi = config('classly.durasi_per_jam_pelajaran');
        $mulaiSekolah = Carbon::parse(config('classly.jam_mulai_sekolah'));
        $jamIni = Carbon::parse($jam);

        return intdiv($mulaiSekolah->diffInMinutes($jamIni), $durasi) + 1;
    }
}