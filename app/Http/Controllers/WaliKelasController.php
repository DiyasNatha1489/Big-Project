<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        $kelas = auth()->user()->kelasDiampu;

        if (!$kelas) {
            return view('wali-kelas.dashboard', [
                'kelas' => null,
                'totalSiswa' => 0,
                'hadirHariIni' => 0,
                'perluVerifikasiHariIni' => 0,
                'alpaHariIni' => 0,
                'presensiTerbaru' => collect(),
                'kegiatanTerdekat' => collect(),
            ]);
        }

        $totalSiswa = $kelas->siswa()->count();

        $tanggal = Carbon::today()->toDateString();
        $sesiHariIni = SesiPresensi::where('kelas_id', $kelas->id)->where('tanggal', $tanggal)->first();

        $hadirHariIni = 0;
        $perluVerifikasiHariIni = 0;
        $alpaHariIni = $totalSiswa;
        $presensiTerbaru = collect();

        if ($sesiHariIni) {
            $hadirHariIni = Presensi::where('sesi_id', $sesiHariIni->id)->where('status', 'hadir')->count();
            $perluVerifikasiHariIni = Presensi::where('sesi_id', $sesiHariIni->id)->where('status', 'perlu_verifikasi')->count();
            $alpaHariIni = $totalSiswa - Presensi::where('sesi_id', $sesiHariIni->id)->count();
            $presensiTerbaru = Presensi::with('siswa')->where('sesi_id', $sesiHariIni->id)->latest()->take(5)->get();
        }

        $kegiatanTerdekat = Kegiatan::where('tanggal', '>=', $tanggal)
            ->where(function ($q) use ($kelas) {
                $q->where('target', 'semua_kelas')
                ->orWhere(function ($q2) use ($kelas) {
                    $q2->where('target', 'kelas_tertentu')->where('kelas_id', $kelas->id);
                });
            })
            ->orderBy('tanggal')
            ->take(3)
            ->get();

        return view('wali-kelas.dashboard', compact(
            'kelas', 'totalSiswa', 'hadirHariIni', 'perluVerifikasiHariIni', 'alpaHariIni',
            'presensiTerbaru', 'kegiatanTerdekat'
        ));
    }
}