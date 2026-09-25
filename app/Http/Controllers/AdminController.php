<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use App\Models\Jadwal;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalGuru = User::where('role', 'wali_kelas')->count();
        $totalKelas = Kelas::count();
        $totalSiswa = User::where('role', 'siswa')->count();

        $tanggal = Carbon::today()->toDateString();
        $sesiHariIni = SesiPresensi::where('tanggal', $tanggal)->pluck('id');
        $hadirHariIni = Presensi::whereIn('sesi_id', $sesiHariIni)->where('status', 'hadir')->count();
        $perluVerifikasiHariIni = Presensi::whereIn('sesi_id', $sesiHariIni)->where('status', 'perlu_verifikasi')->count();

        $guruTerbaru = User::where('role', 'wali_kelas')->latest()->take(3)->get();

        $kelasRingkas = Kelas::with('waliKelas')->withCount('siswa')->latest()->take(4)->get();

        $namaHariIni = strtolower(Carbon::now()->locale('id')->isoFormat('dddd'));
        $jadwalHariIni = Jadwal::with('kelas')
            ->where('hari', $namaHariIni)
            ->orderBy('jam_mulai')
            ->take(5)
            ->get();

        $kegiatanTerdekat = Kegiatan::with('kelas')
            ->where('tanggal', '>=', $tanggal)
            ->orderBy('tanggal')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalGuru', 'totalKelas', 'totalSiswa', 'hadirHariIni', 'perluVerifikasiHariIni',
            'guruTerbaru', 'kelasRingkas', 'jadwalHariIni', 'kegiatanTerdekat'
        ));
    }
}