<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode_unik', 'wali_kelas_id', 'latitude', 'longitude'];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function sesiPresensi()
    {
        return $this->hasMany(SesiPresensi::class);
    }

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }
}