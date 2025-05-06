<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'nip',
        'nrp',
        'kode_matakuliah',
        'id_tahun_ajar',
        'status',
        'nilai_huruf',
        'nilai_angka',
    ];

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip');
    }

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nrp');
    }

    // Relasi ke Matakuliah
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'kode_matakuliah');
    }

    // Relasi ke Tahun Ajar
    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class, 'id_tahun_ajar');
    }
}
