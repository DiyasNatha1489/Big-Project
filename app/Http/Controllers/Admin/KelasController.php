<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guru = User::where('role', 'wali_kelas')->get();
        return view('admin.kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Kelas::create([
            'nama' => $request->nama,
            'kode_unik' => strtoupper(Str::random(6)),
            'wali_kelas_id' => $request->wali_kelas_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(Kelas $kela)
    {
        $guru = User::where('role', 'wali_kelas')->get();
        return view('admin.kelas.edit', compact('kela', 'guru'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $kela->update($request->only('nama', 'wali_kelas_id', 'latitude', 'longitude'));

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}