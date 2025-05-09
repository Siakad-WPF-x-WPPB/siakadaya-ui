<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'prodi_id',
        'kelas_id',
        'nrp',
        'nama',
        'jenis_kelamin',
        'telepon',
        'email',
        'password',
        'agama',
        'semester',
        'tanggal_lahir',
        'tanggal_masuk',
        'status',
        'alamat_jalan',
        'provinsi',
        'kode_pos',
        'negara',
        'kelurahan',
        'kecamatan',
        'kota'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}

