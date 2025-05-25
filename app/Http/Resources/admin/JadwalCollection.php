<?php

namespace App\Http\Resources\admin;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JadwalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get DataTables parameters
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $searchValue = $request->get('search')['value'] ?? '';

        // Get sort parameters
        $orderColumnIndex = $request->get('order')[0]['column'] ?? 2;
        $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';

        // Map column index to actual column name
        $columns = [
            2 => 'kelas_display',
            3 => 'dosen',
            4 => 'matakuliah',
            5 => 'ruangan',
            6 => 'waktu',
            7 => 'tahun_ajar',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // Get total count first (before any filtering)
        $totalRecords = Jadwal::count();

        // Start main query with relationships
        $baseQuery = Jadwal::with([
            'kelas.programStudi',
            'dosen',
            'matakuliah',
            'ruangan_nama',
            'tahunAjar',
        ]);

        // Clone for filtered count
        $filteredQuery = clone $baseQuery;

        // Apply search if provided
        if (!empty($searchValue)) {
            $searchClosure = function($q) use ($searchValue) {
                $q->where('hari', 'like', "%{$searchValue}%")
                  ->orWhere('jam_mulai', 'like', "%{$searchValue}%")
                  ->orWhere('jam_selesai', 'like', "%{$searchValue}%")
                  ->orWhereHas('kelas', function($q) use ($searchValue) {
                      $q->where('pararel', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('dosen', function($q) use ($searchValue) {
                      $q->where('nama', 'like', "%{$searchValue}%")
                        ->orWhere('nip', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('matakuliah', function($q) use ($searchValue) {
                      $q->where('nama', 'like', "%{$searchValue}%")
                        ->orWhere('kode', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('ruangan', function($q) use ($searchValue) {
                      $q->where('nama', 'like', "%{$searchValue}%")
                        ->orWhere('kode', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('kelas.programStudi', function($q) use ($searchValue) {
                      $q->where('nama', 'like', "%{$searchValue}%")
                        ->orWhere('kode', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('tahunAjar', function($q) use ($searchValue) {
                      $q->where('semester', 'like', "%{$searchValue}%")
                        ->orWhere('tahun_mulai', 'like', "%{$searchValue}%")
                        ->orWhere('tahun_akhir', 'like', "%{$searchValue}%");
                  });
            };

            $baseQuery->where($searchClosure);
            $filteredQuery->where($searchClosure);
        }

        // Get filtered count
        $filteredRecords = $filteredQuery->count();

        // Add sorting - FIXED: Remove addOrderBy()
        if ($orderColumn === 'kelas_display') {
            $baseQuery->leftJoin('kelas', 'jadwal.kelas_id', '=', 'kelas.id')
                      ->leftJoin('program_studi', 'kelas.prodi_id', '=', 'program_studi.id')
                      ->orderBy('program_studi.kode', $orderDirection)
                      ->orderBy('kelas.pararel', $orderDirection)
                      ->select('jadwal.*');
        } elseif ($orderColumn === 'matakuliah_nama') {
            $baseQuery->leftJoin('matakuliah', 'jadwal.mk_id', '=', 'matakuliah.id')
                      ->orderBy('matakuliah.nama', $orderDirection)
                      ->select('jadwal.*');
        } elseif ($orderColumn === 'dosen_nama') {
            $baseQuery->leftJoin('dosen', 'jadwal.dosen_id', '=', 'dosen.id')
                      ->orderBy('dosen.nama', $orderDirection)
                      ->select('jadwal.*');
        } elseif ($orderColumn === 'tahun_ajar') {
            $baseQuery->leftJoin('tahun_ajar', 'jadwal.tahun_ajar_id', '=', 'tahun_ajar.id')
                      ->orderBy('tahun_ajar.tahun_mulai', $orderDirection)
                      ->orderBy('tahun_ajar.semester', $orderDirection)
                      ->select('jadwal.*');
        } elseif ($orderColumn === 'ruangan_nama') {
            $baseQuery->leftJoin('ruangan', 'jadwal.ruangan_id', '=', 'ruangan.id')
                      ->orderBy('ruangan.nama', $orderDirection)
                      ->select('jadwal.*');
        } elseif ($orderColumn === 'waktu') {
            // FIXED: Use orderBy() instead of addOrderBy()
            $baseQuery->orderBy('hari', $orderDirection)
                      ->orderBy('jam_mulai', $orderDirection);
        } else {
            $baseQuery->orderBy('jadwal.id', $orderDirection);
        }

        // Apply pagination and get data
        $jadwalData = $baseQuery->offset($start)
                                ->limit($length)
                                ->get();

        // Format data
        $data = $jadwalData->map(function ($jadwal) {
            return [
                'id' => $jadwal->id,
                'hari' => $jadwal->hari ?? 'No Day',
                'jam_mulai' => $jadwal->jam_mulai ? date('H:i', strtotime($jadwal->jam_mulai)) : '',
                'jam_selesai' => $jadwal->jam_selesai ? date('H:i', strtotime($jadwal->jam_selesai)) : '',
                'waktu' => $jadwal->jam_mulai && $jadwal->jam_selesai
                    ? date('H:i', strtotime($jadwal->jam_mulai)) . ' - ' . date('H:i', strtotime($jadwal->jam_selesai))
                    : 'No Time',

                // Kelas information
                'kelas' => $jadwal->kelas ? $jadwal->kelas->pararel : 'No Class',

                // Program Studi from Kelas
                'program_studi' => $jadwal->kelas && $jadwal->kelas->programStudi
                    ? $jadwal->kelas->programStudi->nama
                    : 'No Program Studi',
                'kode_prodi' => $jadwal->kelas && $jadwal->kelas->programStudi
                    ? $jadwal->kelas->programStudi->kode
                    : '',

                // Combined display for kelas with prodi
                'kelas_display' => ($jadwal->kelas && $jadwal->kelas->programStudi
                    ? $jadwal->kelas->programStudi->kode . '-'
                    : '') . ($jadwal->kelas ? $jadwal->kelas->pararel : 'Unknown'),

                // Mata Kuliah information
                'matakuliah' => $jadwal->matakuliah ? $jadwal->matakuliah->nama : 'No Subject',
                'kode_mk' => $jadwal->matakuliah ? $jadwal->matakuliah->kode : '',
                'sks' => $jadwal->matakuliah ? $jadwal->matakuliah->sks : 0,
                'tipe_mk' => $jadwal->matakuliah ? $jadwal->matakuliah->tipe : '',

                // Dosen information
                'dosen' => $jadwal->dosen ? $jadwal->dosen->nama : 'No Lecturer',
                'nip_dosen' => $jadwal->dosen ? $jadwal->dosen->nip : '',

                // Ruangan information
                'ruangan' => $jadwal->ruangan ? $jadwal->ruangan->nama : 'No Room',
                'kode_ruangan' => $jadwal->ruangan ? $jadwal->ruangan->kode : '',
                'gedung' => $jadwal->ruangan ? $jadwal->ruangan->gedung : '',

                // Tahun Ajar information
                'tahun_ajar' => $jadwal->tahunAjar ?
                    $jadwal->tahunAjar->semester . ' ' . $jadwal->tahunAjar->tahun_mulai . '/' . $jadwal->tahunAjar->tahun_akhir
                    : 'No Academic Year',
                'semester' => $jadwal->tahunAjar ? $jadwal->tahunAjar->semester : '',
                'status_tahun_ajar' => $jadwal->tahunAjar ? $jadwal->tahunAjar->status : '',
            ];
        })->toArray();

        // Return formatted response for DataTables
        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
