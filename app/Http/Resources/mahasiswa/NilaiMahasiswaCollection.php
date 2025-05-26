<?php

namespace App\Http\Resources\mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NilaiMahasiswaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($nilai) {
            return [
                'id' => $nilai->id,
                'nama_matakuliah' => $nilai->frsDetail?->jadwal?->matakuliah?->nama ?? null,
                'kode_matakuliah' => $nilai->frsDetail?->jadwal?->matakuliah?->kode ?? null,
                'nilai_angka' => $nilai->nilai_angka,
                'nilai_huruf' => $nilai->nilai_huruf,
                'status' => $nilai->status,
                'semester' => $nilai->frsDetail?->jadwal?->tahunAjar?->semester ?? null,
                'tahun_mulai' => $nilai->frsDetail?->jadwal?->tahunAjar?->tahun_mulai ?? null,
                'tahun_akhir' => $nilai->frsDetail?->jadwal?->tahunAjar?->tahun_akhir ?? null,
            ];
        })->all();
    }
}
