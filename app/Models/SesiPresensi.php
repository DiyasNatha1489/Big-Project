<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiPresensi extends Model
{
    use HasFactory;

    protected $table = 'sesi_presensi';

    protected $fillable = ['kelas_id', 'tanggal', 'buka_pada', 'tutup_pada'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'sesi_id');
    }
}