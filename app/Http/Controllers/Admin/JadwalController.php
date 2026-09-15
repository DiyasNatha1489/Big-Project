<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->input('kelas_id');
        $kelas = Kelas::all();

        $jadwal = Jadwal::with('kelas')
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.index', compact('jadwal', 'kelas', 'kelasId'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.jadwal.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu',
            'mapel' => 'required|string|max:100',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Jadwal::create($request->only('kelas_id', 'hari', 'mapel', 'jam_mulai', 'jam_selesai') + [
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}