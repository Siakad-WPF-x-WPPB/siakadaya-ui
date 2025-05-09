<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tahun_ajar';

    protected $fillable = [
        'semester',
        'tahun'
    ];

    // Relasi ke FRS (jika ada banyak FRS untuk 1 tahun ajar)
    public function frs()
    {
        return $this->hasMany(Frs::class, 'tahun_ajar_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'tahun_ajar_id');
    }
}
