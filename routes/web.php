<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Siswa\AbsenController;
use App\Http\Controllers\WaliKelas\PresensiController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Siswa\JadwalController as SiswaJadwalController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\WaliKelas\KegiatanController as WaliKelasKegiatanController;
use App\Http\Controllers\Siswa\KegiatanController as SiswaKegiatanController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('guru', GuruController::class)->names('admin.guru')->except(['show']);
    Route::resource('kelas', KelasController::class)->names('admin.kelas')->except(['show']);
    Route::get('/rekap', [RekapController::class, 'index'])->name('admin.rekap.index');
    Route::get('/jadwal', [AdminJadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::get('/jadwal/create', [AdminJadwalController::class, 'create'])->name('admin.jadwal.create');
    Route::post('/jadwal', [AdminJadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::delete('/jadwal/bulk', [AdminJadwalController::class, 'bulkDestroy'])->name('admin.jadwal.bulk-destroy');
    Route::put('/jadwal/bulk', [AdminJadwalController::class, 'bulkUpdate'])->name('admin.jadwal.bulk-update');
    Route::delete('/jadwal/{jadwal}', [AdminJadwalController::class, 'destroy'])->name('admin.jadwal.destroy');
    Route::get('/jadwal/{jadwal}/edit', [AdminJadwalController::class, 'edit'])->name('admin.jadwal.edit');
    Route::put('/jadwal/{jadwal}', [AdminJadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::get('/kegiatan', [AdminKegiatanController::class, 'index'])->name('admin.kegiatan.index');
    Route::get('/kegiatan/create', [AdminKegiatanController::class, 'create'])->name('admin.kegiatan.create');
    Route::post('/kegiatan', [AdminKegiatanController::class, 'store'])->name('admin.kegiatan.store');
    Route::delete('/kegiatan/{kegiatan}', [AdminKegiatanController::class, 'destroy'])->name('admin.kegiatan.destroy');
});

Route::middleware(['auth', 'role:wali_kelas', 'paksa.ganti.password'])->prefix('wali-kelas')->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('wali_kelas.dashboard');
    Route::get('/presensi', [PresensiController::class, 'index'])->name('wali_kelas.presensi.index');
    Route::patch('/presensi/{presensi}', [PresensiController::class, 'verifikasi'])->name('wali_kelas.presensi.verifikasi');
    Route::get('/kegiatan', [WaliKelasKegiatanController::class, 'index'])->name('wali_kelas.kegiatan.index');
    Route::post('/kegiatan', [WaliKelasKegiatanController::class, 'store'])->name('wali_kelas.kegiatan.store');
    Route::delete('/kegiatan/{kegiatan}', [WaliKelasKegiatanController::class, 'destroy'])->name('wali_kelas.kegiatan.destroy');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::get('/absen', [AbsenController::class, 'create'])->name('siswa.absen.create');
    Route::post('/absen', [AbsenController::class, 'store'])->name('siswa.absen.store');
    Route::get('/jadwal', [SiswaJadwalController::class, 'index'])->name('siswa.jadwal.index');
    Route::get('/kegiatan', [SiswaKegiatanController::class, 'index'])->name('siswa.kegiatan.index');
    Route::post('/absen/izin', [AbsenController::class, 'storeIzin'])->name('siswa.absen.izin');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/foto', [ProfileController::class, 'updateFoto'])->name('profile.foto.update');
});

require __DIR__.'/auth.php';
