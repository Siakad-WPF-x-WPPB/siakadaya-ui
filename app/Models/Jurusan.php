<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';

    protected $fillable = [
        'nama_jurusan',
    ];

    // Relasi ke Mahasiswa (satu jurusan punya banyak mahasiswa)
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'kode_jurusan');
    }

    // Relasi ke Dosen (satu jurusan punya banyak dosen)
    public function dosen()
    {
        return $this->hasMany(Dosen::class, 'kode_jurusan');
    }
}
