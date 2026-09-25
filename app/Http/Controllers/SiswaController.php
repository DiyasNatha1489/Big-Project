<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $kelas = $user->kelas;

        if (!$kelas) {
            return view('siswa.dashboard', [
                'kelas' => null,
                'statusAbsenHariIni' => null,
                'jadwalHariIni' => collect(),
                'kegiatanTerdekat' => collect(),
            ]);
        }

        $tanggal = Carbon::today()->toDateString();
        $sesiHariIni = SesiPresensi::where('kelas_id', $kelas->id)->where('tanggal', $tanggal)->first();

        $statusAbsenHariIni = null;
        if ($sesiHariIni) {
            $presensi = Presensi::where('sesi_id', $sesiHariIni->id)->where('siswa_id', $user->id)->first();
            $statusAbsenHariIni = $presensi?->status;
        }

        $namaHariIni = strtolower(Carbon::now()->locale('id')->isoFormat('dddd'));
        $jadwalHariIni = $kelas->jadwal()->where('hari', $namaHariIni)->orderBy('jam_mulai')->get();

        $kegiatanTerdekat = Kegiatan::where('tanggal', '>=', $tanggal)
            ->where(function ($q) use ($kelas) {
                $q->where('target', 'semua_kelas')
                  ->orWhere(fn ($q2) => $q2->where('target', 'kelas_tertentu')->where('kelas_id', $kelas->id));
            })
            ->orderBy('tanggal')
            ->take(3)
            ->get();

        return view('siswa.dashboard', compact('kelas', 'statusAbsenHariIni', 'jadwalHariIni', 'kegiatanTerdekat'));
    }
}