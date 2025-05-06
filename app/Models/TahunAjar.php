<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjar extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajar';

    protected $fillable = [
        'tahun_ajaran',
    ];

    // Relasi ke FRS (jika ada banyak FRS untuk 1 tahun ajar)
    public function frs()
    {
        return $this->hasMany(Frs::class, 'id_tahun_ajar');
    }
}
