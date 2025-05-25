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
        'tahun_mulai',
        'tahun_akhir',
        'status'
    ];

    public function frs()
    {
        return $this->hasMany(Frs::class, 'tahun_ajar_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'tahun_ajar_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'tahun_ajar_id');
    }
}
