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
        'prodi_id',
        'nip',
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

    public function scopeJabatan($query, $jabatan)
    {
        return $query->where('jabatan', $jabatan);
    }

    public function scopeGolonganAkhir($query, $golongan_akhir)
    {
        return $query->where('golongan_akhir', $golongan_akhir);
    }

    public function scopeProdi($query, $prodi_id)
    {
        return $query->where('prodi_id', $prodi_id);
    }

    public function scopeWali($query)
    {
        return $query->where('is_wali', true);
    }

    public function scopeRegular($query)
    {
        return $query->where('is_wali', false);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'dosen_id');
    }

    // * Wali relationship
    // TODO: Dosen wali can only have one class
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'dosen_id');
    }

    // * Teaching relationship
    // TODO: Dosen can teach multiple classes to teach multiple students
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'dosen_id');
    }

    // * Classes taught by the lecturer
    // TODO: Get all classes taught by the lecturer through the jadwal relationship
    public function kelasDiajar()
    {
        return $this->hasManyThrough(
            Kelas::class,
            Jadwal::class,
            'dosen_id',
            'id',
            'id',
            'kelas_id'
        );
    }
}
