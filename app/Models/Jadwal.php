<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = ['kelas_id', 'hari', 'mapel', 'jam_mulai', 'jam_selesai', 'dibuat_oleh'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}