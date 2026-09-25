<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $kelas = $user->kelas;

        $sesi = $this->ambilAtauBuatSesiHariIni($kelas->id);

        $sudahAbsen = Presensi::where('sesi_id', $sesi->id)
            ->where('siswa_id', $user->id)
            ->exists();

        $sekarang = Carbon::now();
        $jendelaAktif = $sekarang->between($sesi->buka_pada, $sesi->tutup_pada);

        return view('siswa.absen.create', compact('sesi', 'sudahAbsen', 'jendelaAktif', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:5120',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = auth()->user();
        $kelas = $user->kelas;
        $sesi = $this->ambilAtauBuatSesiHariIni($kelas->id);

        $sekarang = Carbon::now();
        if (!$sekarang->between($sesi->buka_pada, $sesi->tutup_pada)) {
            return back()->with('error', 'Jendela absen hari ini sudah tutup atau belum dibuka.');
        }

        if (Presensi::where('sesi_id', $sesi->id)->where('siswa_id', $user->id)->exists()) {
            return back()->with('error', 'Kamu sudah absen untuk sesi ini.');
        }

        $jarak = $this->hitungJarakMeter(
            $kelas->latitude, $kelas->longitude,
            $request->latitude, $request->longitude
        );

        $status = $jarak <= config('classly.radius_meter') ? 'hadir' : 'perlu_verifikasi';

        $path = $request->file('foto')->store('presensi', 'public');

        Presensi::create([
            'sesi_id' => $sesi->id,
            'siswa_id' => $user->id,
            'tipe' => 'absen_foto',
            'foto' => $path,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $status,
        ]);

        $pesan = $status === 'hadir'
            ? 'Absen berhasil! Kamu tercatat hadir.'
            : 'Absen tersimpan, tapi lokasimu di luar radius sekolah. Wali kelas akan memverifikasi manual.';

        return redirect()->route('siswa.dashboard')->with('success', $pesan);
    }

    public function storeIzin(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|max:5120',
        ]);

        $user = auth()->user();
        $kelas = $user->kelas;
        $sesi = $this->ambilAtauBuatSesiHariIni($kelas->id);

        if (Presensi::where('sesi_id', $sesi->id)->where('siswa_id', $user->id)->exists()) {
            return back()->with('error', 'Kamu sudah tercatat untuk hari ini.');
        }

        $path = $request->file('foto')->store('presensi', 'public');

        Presensi::create([
            'sesi_id' => $sesi->id,
            'siswa_id' => $user->id,
            'tipe' => 'surat_izin',
            'foto' => $path,
            'latitude' => null,
            'longitude' => null,
            'status' => 'izin',
        ]);

        return redirect()->route('siswa.dashboard')->with('success', 'Surat izin berhasil dikirim.');
    }

    private function ambilAtauBuatSesiHariIni(int $kelasId): SesiPresensi
    {
        $tanggal = Carbon::today()->toDateString();

        return SesiPresensi::firstOrCreate(
            ['kelas_id' => $kelasId, 'tanggal' => $tanggal],
            [
                'buka_pada' => $tanggal.' '.config('classly.absen_buka'),
                'tutup_pada' => $tanggal.' '.config('classly.absen_tutup'),
            ]
        );
    }

    private function hitungJarakMeter($lat1, $lon1, $lat2, $lon2): float
    {
        $r = 6371000; // radius bumi dalam meter
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($deltaLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $r * $c;
    }
}