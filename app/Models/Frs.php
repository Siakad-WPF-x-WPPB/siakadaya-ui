<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frs extends Model
{
    use HasFactory;

    protected $table = 'frs';

    protected $fillable = [
        'nrp',
        'nip',
        'id_tahun_ajar',
        'semester',
        'tanggal_pengisian',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nrp');
    }

    // Relasi ke Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip');
    }

    // Relasi ke Tahun Ajar
    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class, 'id_tahun_ajar');
    }
}
