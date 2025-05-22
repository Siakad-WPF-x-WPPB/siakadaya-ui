<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Frs extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'frs';

    protected $fillable = ['id', 'mahasiswa_id', 'tahun_ajar_id', 'tanggal_pengisian'];

    public function frs_detail()
    {
        return $this->hasMany(FrsDetail::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
