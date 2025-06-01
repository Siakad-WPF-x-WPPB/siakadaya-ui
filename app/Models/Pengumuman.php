<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'id',
        'admin_id',
        'judul',
        'isi',
        'tanggal_dibuat',
        'status',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
