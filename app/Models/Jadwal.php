<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jadwal';

    protected $fillable = [
        'kelas_id',
        'dosen_id',
        'mk_id',
        'ruangan_id',
        'tahun_ajar_id',
        'jam_mulai',
        'jam_selesai',
        'hari',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class, 'tahun_ajar_id');
    }

    public function frsDetail()
    {
        return $this->hasMany(FrsDetail::class, 'jadwal_id');
    }
}
