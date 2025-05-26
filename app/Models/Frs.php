<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frs extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'frs';

    protected $fillable = [
        'mahasiswa_id',
        'tanggal_pengisian',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function frsDetail()
    {
        return $this->hasMany(FrsDetail::class, 'frs_id');
    }
    
    public function tahunAjar()
    {
        return $this->belongsTo(TahunAjar::class);
    }
}
