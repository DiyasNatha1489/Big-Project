<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $kelas = auth()->user()->kelas;

        $kegiatan = Kegiatan::where('target', 'semua_kelas')
            ->orWhere(fn ($q) => $q->where('target', 'kelas_tertentu')->where('kelas_id', $kelas?->id))
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.kegiatan.index', compact('kegiatan'));
    }
}