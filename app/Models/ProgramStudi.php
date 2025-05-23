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

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }

    public function dosen()
    {
        return $this->hasMany(Dosen::class, 'prodi_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'prodi_id');
    }

    public function matakuliah()
    {
        return $this->hasMany(Matakuliah::class, 'prodi_id');
    }
}
