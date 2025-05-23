<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FrsDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'frs_detail';

    protected $fillable = [
      'frs_id',
      'jadwal_id',
      'status',
      'tanggal_persetujuan',
    ];

    protected $casts = [
        'tanggal_persetujuan' => 'date',
    ];

    public function frs()
    {
        return $this->belongsTo(Frs::class, 'frs_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}
