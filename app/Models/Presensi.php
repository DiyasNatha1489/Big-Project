<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'sesi_id', 'siswa_id', 'tipe', 'foto',
        'latitude', 'longitude', 'status',
        'diverifikasi_oleh', 'catatan_verifikasi',
    ];

    public function sesiPresensi()
    {
        return $this->belongsTo(SesiPresensi::class, 'sesi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function diverifikasiOleh()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}