<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mahasiswa extends Authenticatable
{
    use HasFactory, HasUuids, HasApiTokens, Notifiable;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'prodi_id',
        'kelas_id',
        'nrp',
        'nama',
        'jenis_kelamin',
        'telepon',
        'email',
        'password',
        'agama',
        'semester',
        'tanggal_lahir',
        'tanggal_masuk',
        'status',
        'alamat_jalan',
        'provinsi',
        'kode_pos',
        'negara',
        'kelurahan',
        'kecamatan',
        'kota'
    ];

    protected $hidden = [
      'password',
      'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'password' => 'hashed'
    ];

    public function getActiveAttribute()
    {
        return $this->status === 'Aktif';
    }

    public function getFullAddressAttribute()
    {
        return "{$this->alamat_jalan}, {$this->kelurahan}, {$this->kecamatan}, {$this->kota}, {$this->provinsi}, {$this->kode_pos}, {$this->negara}";
    }

    public function scopeByProdi($query, $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function detailJadwal()
    {
        return $this->hasMany(DetailJadwal::class, 'mahasiswa_id');
    }

    public function frs()
    {
        return $this->hasMany(Frs::class, 'mahasiswa_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_id');
    }
}

