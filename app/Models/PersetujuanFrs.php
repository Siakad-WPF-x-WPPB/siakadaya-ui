<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersetujuanFrs extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_frs';

    protected $fillable = [
        'id_frs',
        'id_jadwal',
        'nip',
        'kode_matakuliah',
        'status',
    ];

    // Relasi ke FRS
    public function frs()
    {
        return $this->belongsTo(Frs::class, 'id_frs');
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip');
    }

    // Relasi ke Matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_matakuliah');
    }
}
