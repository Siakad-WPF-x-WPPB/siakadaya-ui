<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'kode_jurusan',
        'id_kelas',
        'nama_mahasiswa',
        'jenis_kelamin',
        'telepon',
        'email',
        'password',
        'tanggal_lahir',
        'tanggal_masuk',
        'status',
        'alamat',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'kode_jurusan');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}

