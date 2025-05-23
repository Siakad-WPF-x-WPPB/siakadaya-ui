<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matakuliah extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'matakuliah';

    protected $fillable = [
        'prodi_id',
        'kode',
        'nama',
        'semester',
        'sks',
        'tipe',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'mk_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mk_id');
    }
}
