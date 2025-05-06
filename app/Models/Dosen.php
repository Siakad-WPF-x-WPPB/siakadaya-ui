<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'kode_jurusan',
        'nama_dosen',
        'jenis_kelamin',
        'telepon',
        'email',
        'password',
        'tanggal_lahir',
        'jabatan',
        'golongan_akhir',
        'is_wali',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_wali' => 'boolean',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'kode_jurusan');
    }
}
