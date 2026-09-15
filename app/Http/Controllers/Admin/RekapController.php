<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        $rekap = Kelas::with('waliKelas')->get()->map(function ($kelas) use ($tanggal) {
            $sesi = SesiPresensi::where('kelas_id', $kelas->id)->where('tanggal', $tanggal)->first();

            $totalSiswa = $kelas->siswa()->count();
            $hadir = 0;
            $perluVerifikasi = 0;

            if ($sesi) {
                $hadir = Presensi::where('sesi_id', $sesi->id)->where('status', 'hadir')->count();
                $perluVerifikasi = Presensi::where('sesi_id', $sesi->id)->where('status', 'perlu_verifikasi')->count();
            }

            return [
                'kelas' => $kelas,
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'perlu_verifikasi' => $perluVerifikasi,
                'alpa' => $sesi ? $totalSiswa - Presensi::where('sesi_id', $sesi->id)->count() : $totalSiswa,
                'sesi_dibuka' => (bool) $sesi,
            ];
        });

        return view('admin.rekap.index', compact('rekap', 'tanggal'));
    }
}