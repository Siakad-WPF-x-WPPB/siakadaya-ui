<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ruangan';

    protected $fillable = [
        'kode',
        'nama',
        'gedung'
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'ruangan_id');
    }
}
