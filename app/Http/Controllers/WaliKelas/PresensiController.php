<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\SesiPresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = auth()->user()->kelasDiampu;

        if (!$kelas) {
            return back()->with('error', 'Kamu belum ditugaskan sebagai wali kelas manapun. Hubungi Admin.');
        }

        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        $sesi = SesiPresensi::where('kelas_id', $kelas->id)
            ->where('tanggal', $tanggal)
            ->first();

        $presensi = $sesi
            ? Presensi::with('siswa')->where('sesi_id', $sesi->id)->get()
            : collect();

        $siswaBelumAbsen = $kelas->siswa()
            ->whereNotIn('id', $presensi->pluck('siswa_id'))
            ->get();

        return view('wali-kelas.presensi.index', compact('kelas', 'tanggal', 'sesi', 'presensi', 'siswaBelumAbsen'));
    }

    public function verifikasi(Request $request, Presensi $presensi)
    {
        $request->validate([
            'status' => 'required|in:hadir,telat,izin,perlu_verifikasi',
            'catatan_verifikasi' => 'nullable|string|max:255',
        ]);

        $kelas = auth()->user()->kelasDiampu;

        if (!$kelas || $presensi->sesiPresensi->kelas_id !== $kelas->id) {
            abort(403, 'Kamu tidak bisa mengubah presensi kelas lain.');
        }

        $presensi->update([
            'status' => $request->status,
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'diverifikasi_oleh' => auth()->id(),
        ]);

        return back()->with('success', 'Status presensi berhasil diperbarui.');
    }
}