<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'dosen';

    protected $fillable = [
        'nip',
        'prodi_id',
        'nama',
        'jenis_kelamin',
        'telepon',
        'email',
        'password',
        'tanggal_lahir',
        'jabatan',
        'golongan_akhir',
        'is_wali',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_wali' => 'boolean',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'jadwal_id');
    }
}
