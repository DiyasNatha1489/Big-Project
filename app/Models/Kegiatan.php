<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'judul', 'deskripsi', 'tanggal', 'target',
        'kelas_id', 'dibuat_oleh_role', 'dibuat_oleh_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh_id');
    }
}