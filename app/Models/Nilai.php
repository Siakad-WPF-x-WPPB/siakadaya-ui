<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nilai extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'nilai';

    protected $fillable = [
        'frs_detail_id',
        'status',
        'nilai_huruf',
        'nilai_angka',
    ];

    protected $casts = [
        'nilai_huruf' => 'string',
        'nilai_angka' => 'float',
    ];

    public function frsDetail()
    {
        return $this->belongsTo(FrsDetail::class, 'frs_detail_id');
    }
}
