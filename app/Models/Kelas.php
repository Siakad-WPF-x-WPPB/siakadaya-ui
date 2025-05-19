<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'kelas';

    protected $fillable = [
        'dosen_id',
        'pararel',
    ];

    // Relasi ke Dosen (satu kelas dimiliki oleh satu dosen)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    // Relasi ke Mahasiswa (satu kelas memiliki banyak mahasiswa)
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kelas_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'jadwal_id');
    }
}
