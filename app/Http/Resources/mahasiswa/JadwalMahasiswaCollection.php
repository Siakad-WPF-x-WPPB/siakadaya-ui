<?php

namespace App\Http\Resources\mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JadwalMahasiswaCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($jadwal) {
            return [
                'id' => $jadwal->id,
                'hari' => $jadwal->hari,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
                'matakuliah' => $jadwal->matakuliah->nama,
                'dosen' => $jadwal->dosen->nama,
                'kelas' => $jadwal->kelas->pararel,
                'ruangan' => $jadwal->ruangan->nama,
            ];
        })->all();
    }
}
