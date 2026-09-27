<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with('kelas')->orderBy('tanggal', 'desc')->get();
        $kelas = Kelas::all();
        return view('admin.kegiatan.index', compact('kegiatan', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.kegiatan.create', compact('kelas'));
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
            'target' => 'required|in:semua_kelas,kelas_tertentu',
            'kelas_id' => 'required_if:target,kelas_tertentu|nullable|exists:kelas,id',
        ]);

        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->lokasi,
            'dresscode' => $request->dresscode,
            'peraturan' => $request->peraturan,
            'target' => $request->target,
            'kelas_id' => $request->target === 'kelas_tertentu' ? $request->kelas_id : null,
            'dibuat_oleh_role' => 'admin',
            'dibuat_oleh_id' => auth()->id(),
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan)
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
            'target' => 'required|in:semua_kelas,kelas_tertentu',
            'kelas_id' => 'required_if:target,kelas_tertentu|nullable|exists:kelas,id',
        ]);

        $kegiatan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->lokasi,
            'dresscode' => $request->dresscode,
            'peraturan' => $request->peraturan,
            'target' => $request->target,
            'kelas_id' => $request->target === 'kelas_tertentu' ? $request->kelas_id : null,
        ]);

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}