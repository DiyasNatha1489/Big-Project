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
        return view('admin.kegiatan.index', compact('kegiatan'));
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
            'target' => 'required|in:semua_kelas,kelas_tertentu',
            'kelas_id' => 'required_if:target,kelas_tertentu|nullable|exists:kelas,id',
        ]);

        Kegiatan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'target' => $request->target,
            'kelas_id' => $request->target === 'kelas_tertentu' ? $request->kelas_id : null,
            'dibuat_oleh_role' => 'admin',
            'dibuat_oleh_id' => auth()->id(),
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}