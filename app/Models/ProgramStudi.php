<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramStudi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'program_studi';

    protected $fillable = [
        'nama',
        'kode'
    ];

    // Relasi ke Mahasiswa (satu jurusan punya banyak mahasiswa)
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }

    // Relasi ke Dosen (satu jurusan punya banyak dosen)
    public function dosen()
    {
        return $this->hasMany(Dosen::class, 'prodi_id');
    }
}
