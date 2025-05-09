<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'kode',
        'nama',
        'gedung'
    ];

    // Relasi ke Jadwal (jika ruangan digunakan di jadwal kuliah)
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'ruangan_id');
    }
}
