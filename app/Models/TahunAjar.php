<?php

namespace App\Models;

use Carbon\Carbon;
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
        'mulai_frs',
        'selesai_frs',
        'mulai_edit_frs',
        'selesai_edit_frs',
        'mulai_drop_frs',
        'selesai_drop_frs',
        'status'
    ];

    protected $casts = [
        'mulai_frs' => 'date',
        'selesai_frs' => 'date',
        'mulai_edit_frs' => 'date',
        'selesai_edit_frs' => 'date',
        'mulai_drop_frs' => 'date',
        'selesai_drop_frs' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    // Helper methods for FRS period checks
    public function isFrsOpen()
    {
        $now = Carbon::now()->toDateString();
        return $this->mulai_frs && $this->selesai_frs &&
                $now >= $this->mulai_frs->toDateString() &&
                $now <= $this->selesai_frs->toDateString();
    }

    public function isFrsEditOpen()
    {
        $now = Carbon::now()->toDateString();
        return $this->mulai_edit_frs && $this->selesai_edit_frs &&
                $now >= $this->mulai_edit_frs->toDateString() &&
                $now <= $this->selesai_edit_frs->toDateString();
    }

    public function isFrsDropOpen()
    {
        $now = Carbon::now()->toDateString();
        return $this->mulai_drop_frs && $this->selesai_drop_frs &&
                $now >= $this->mulai_drop_frs->toDateString() &&
                $now <= $this->selesai_drop_frs->toDateString();
    }

    public function getFrsStatus()
    {
        $now = Carbon::now()->toDateString();

        if ($this->isFrsOpen()) {
            return 'frs_open';
        } elseif ($this->isFrsEditOpen()) {
            return 'edit_open';
        } elseif ($this->isFrsDropOpen()) {
            return 'drop_open';
        } elseif ($this->mulai_frs && $now < $this->mulai_frs->toDateString()) {
            return 'not_started';
        } else {
            return 'closed';
        }
    }

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
