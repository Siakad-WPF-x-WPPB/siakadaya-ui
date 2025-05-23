<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nilai extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nilai';

    protected $fillable = [
        'dosen_id',
        'mahasiswa_id',
        'mk_id',
        'tahun_ajar_id',
        'status',
        'nilai_huruf',
        'nilai_angka',
    ];

    protected $casts = [
        'nilai_huruf' => 'string',
        'nilai_angka' => 'float',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id');
    }

    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id');
    }
}
