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

        $kegiatan = Kegiatan::where('target', 'semua_kelas')
            ->orWhere(function ($q) use ($kelas) {
                $q->where('target', 'kelas_tertentu')->where('kelas_id', $kelas->id);
            })
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
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable|after:jam_mulai',
            'lokasi' => 'nullable|string|max:150',
            'dresscode' => 'nullable|string|max:100',
            'peraturan' => 'nullable|string',
        ]);

        $kelas = auth()->user()->kelasDiampu;

        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->lokasi,
            'dresscode' => $request->dresscode,
            'peraturan' => $request->peraturan,
            'target' => 'kelas_tertentu',
            'kelas_id' => $kelas->id,
            'dibuat_oleh_role' => 'wali_kelas',
            'dibuat_oleh_id' => auth()->id(),
        ]);

        return back()->with('success', 'Kegiatan kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $kelas = auth()->user()->kelasDiampu;

        if (!$kelas || $kegiatan->kelas_id !== $kelas->id || $kegiatan->dibuat_oleh_role !== 'wali_kelas') {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable|after:jam_mulai',
            'lokasi' => 'nullable|string|max:150',
            'dresscode' => 'nullable|string|max:100',
            'peraturan' => 'nullable|string',
        ]);

        $kegiatan->update($request->only(
            'judul', 'deskripsi', 'tanggal', 'jam_mulai', 'jam_selesai', 'lokasi', 'dresscode', 'peraturan'
        ));

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
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