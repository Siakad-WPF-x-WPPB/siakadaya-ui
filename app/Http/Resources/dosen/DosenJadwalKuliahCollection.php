<?php

namespace App\Http\Resources\dosen;

use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class DosenJadwalKuliahCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10); // Default records per page
        $searchValue = $request->input('search.value', ''); // DataTables search value

        $dosenId = Auth::guard('dosen')->id();

        if (!$dosenId) {
            // Handle cases where lecturer is not authenticated, though middleware should typically cover this
            return [
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'User not authenticated or Dosen ID not found.'
            ];
        }

        // Base query for the authenticated lecturer's schedules
        $query = Jadwal::where('dosen_id', $dosenId)
            ->with(['matakuliah', 'kelas', 'ruangan']); // Eager load relations

        // Get total records before any filtering
        $totalRecords = $query->count();

        // Apply search functionality
        if (!empty($searchValue)) {
            $query->where(function (Builder $q) use ($searchValue) {
                $q->whereHas('matakuliah', function (Builder $sq) use ($searchValue) {
                    $sq->where('nama', 'like', '%' . $searchValue . '%')
                        ->orWhere('kode_mk', 'like', '%' . $searchValue . '%'); // Assuming 'kode_mk' field exists
                })
                    ->orWhereHas('kelas', function (Builder $sq) use ($searchValue) {
                        // Adjust 'nama_kelas' if your field name is different (e.g., 'nama')
                        $sq->where('nama_kelas', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhereHas('ruangan', function (Builder $sq) use ($searchValue) {
                        // Adjust 'nama_ruangan' if your field name is different (e.g., 'nama')
                        $sq->where('nama_ruangan', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhere('hari', 'like', '%' . $searchValue . '%');
            });
        }

        $filteredRecords = $query->count();

        $query->orderBy('hari')->orderBy('jam_mulai');

        if ($length != -1) {
            $query->offset($start)->limit($length);
        }

        $jadwals = $query->get();

        // Transform the data for the response
        $data = $jadwals->map(function ($jadwal) {
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

                // For debugging
                'created_at' => $jadwal->created_at ? $jadwal->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $jadwal->updated_at ? $jadwal->updated_at->format('Y-m-d H:i:s') : null,
            ];
        });

        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
