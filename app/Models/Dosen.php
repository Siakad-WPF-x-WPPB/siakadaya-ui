<?php

namespace App\Models;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dosen extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

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
        'password' => 'hashed',
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
