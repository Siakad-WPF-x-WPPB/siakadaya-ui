<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersetujuanFrs extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'persetujuan_frs';

    protected $fillable = [
        'frs_id',
        'jadwal_id',
        'status',
        'tanggal_persetujuan',
    ];

    // Relasi ke FRS
    public function frs()
    {
        return $this->belongsTo(Frs::class, 'frs_id');
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

}
