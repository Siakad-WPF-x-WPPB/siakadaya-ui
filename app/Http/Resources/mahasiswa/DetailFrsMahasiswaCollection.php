<?php

namespace App\Http\Resources\mahasiswa; // Sesuaikan dengan namespace Anda

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class DetailFrsMahasiswaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($frsDetail) {
            $jadwal = $frsDetail->jadwal;
            return [
                'id' => $frsDetail->id,
                'status' => $frsDetail->status,
                'hari' => $jadwal?->hari,
                'jam_mulai' => $jadwal?->jam_mulai,
                'jam_selesai' => $jadwal?->jam_selesai,
                'nama_matakuliah' => $jadwal?->matakuliah?->nama ?? null,
                'tipe_matakuliah' => $jadwal?->matakuliah?->tipe ?? null,
                'sks' => $jadwal?->matakuliah?->sks ?? null,
                'nama_dosen' => $jadwal?->dosen?->nama ?? null,
                'kelas' => $jadwal?->kelas?->pararel ?? null,
                'ruangan' => $jadwal?->ruangan?->nama ?? null,
                'tahun_ajar' => $frsDetail->jadwal?->tahunAjar?->tahun_mulai ?? null,
                'tahun_berakhir' => $frsDetail->jadwal?->tahunAjar?->tahun_akhir ?? null,
                'semester' => $frsDetail->jadwal?->tahunAjar?->semester ?? null,
                'dosen_wali' => $jadwal?->kelas->dosen?->nama ?? null,
            ];
        })->all();
    }
}
