<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kelas = auth()->user()->kelasDiampu;

        if (!$kelas) {
            return back()->with('error', 'Kamu belum ditugaskan sebagai wali kelas manapun.');
        }

        $kegiatan = Kegiatan::where('kelas_id', $kelas->id)
            ->where('dibuat_oleh_role', 'wali_kelas')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('wali-kelas.kegiatan.index', compact('kelas', 'kegiatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $kelas = auth()->user()->kelasDiampu;

        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'target' => 'kelas_tertentu',
            'kelas_id' => $kelas->id,
            'dibuat_oleh_role' => 'wali_kelas',
            'dibuat_oleh_id' => auth()->id(),
        ]);

        return back()->with('success', 'Kegiatan kelas berhasil ditambahkan.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kelas = auth()->user()->kelasDiampu;

        if (!$kelas || $kegiatan->kelas_id !== $kelas->id) {
            abort(403);
        }

        $kegiatan->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}