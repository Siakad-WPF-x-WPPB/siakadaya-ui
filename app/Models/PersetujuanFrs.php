<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersetujuanFrs extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'persetujuan_frs';

    protected $fillable = ['id', 'frs_detail_id', 'status', 'tanggal_persetujuan'];

    public function frs_detail()
    {
        return $this->belongsTo(FrsDetail::class);
    }
}
