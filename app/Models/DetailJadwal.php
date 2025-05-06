<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailJadwal extends Model
{
    use HasFactory;

    protected $table = 'detail_jadwal';

    protected $fillable = [
        'id_jadwal',
        'nrp',
    ];

    // Relasi ke jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    // Relasi ke mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nrp');
    }
}
