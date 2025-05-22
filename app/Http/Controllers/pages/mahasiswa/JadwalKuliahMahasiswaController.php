<?php

namespace App\Http\Controllers\pages\mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Resources\mahasiswa\JadwalMahasiswaCollection;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalKuliahMahasiswaController extends Controller
{
    protected $urutanHari = [
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
    ];

    public function semua(Request $request)
    {
        $jadwal = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan'])
            ->whereHas('kelas.mahasiswa', function ($query) {
                $query->where('id', auth('mahasiswa_api')->id());
            })
            ->get();

        return new JadwalMahasiswaCollection($jadwal);
    }

    public function hariIni(Request $request)
    {
        $hariIni = ucfirst(Carbon::now()->locale('id')->isoFormat('dddd'));

        $jadwal = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan'])
            ->where('hari', $hariIni)
            ->whereHas('kelas.mahasiswa', function ($query) {
                $query->where('id', auth('mahasiswa_api')->id());
            })
            ->get();

        return new JadwalMahasiswaCollection($jadwal);
    }

    public function besok(Request $request)
    {
        $hariIni = ucfirst(Carbon::now()->locale('id')->isoFormat('dddd'));

        // Dapatkan index hari ini
        $indexHariIni = array_search($hariIni, $this->urutanHari);

        // Ambil hari-hari setelah hari ini
        $hariMendatang = array_slice($this->urutanHari, $indexHariIni + 1);

        $jadwal = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan'])
            ->whereIn('hari', $hariMendatang)
            ->whereHas('kelas.mahasiswa', function ($query) {
                $query->where('id', auth('mahasiswa_api')->id());
            })
            ->get();

        return new JadwalMahasiswaCollection($jadwal);
    }
}
