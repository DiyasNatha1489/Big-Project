<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    public function index()
    {
        $kelas = auth()->user()->kelas;

        if (!$kelas) {
            return back()->with('error', 'Kamu belum terdaftar di kelas manapun.');
        }

        $jadwal = Jadwal::where('kelas_id', $kelas->id)
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('siswa.jadwal.index', compact('kelas', 'jadwal'));
    }
}