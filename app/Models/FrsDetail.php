<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrsDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'frs_detail';

    protected $fillable = ['id', 'frs_id', 'jadwal_id'];

    public function frs()
    {
        return $this->belongsTo(Frs::class);
    }

    public function persetujuan()
    {
        return $this->hasOne(PersetujuanFrs::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
