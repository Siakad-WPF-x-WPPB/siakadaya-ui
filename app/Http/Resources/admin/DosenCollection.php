<?php

namespace App\Http\Resources\admin;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DosenCollection extends ResourceCollection
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
        $orderColumnIndex = $request->get('order')[0]['column'] ?? 1;
        $orderDirection = $request->get('order')[0]['dir'] ?? 'asc';

        // Map column index to actual column name
        $columns = [
            1 => 'nip',
            2 => 'nama',
            3 => 'telepon',
            4 => 'program_studi',
            5 => 'jenis_kelamin',
            6 => 'jabatan',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'nip';

        // Start query
        $query = Dosen::query();

        // Apply search if provided
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('nip', 'like', "%{$searchValue}%")
                  ->orWhere('nama', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhereHas('programStudi', function($q) use ($searchValue) {
                      $q->where('nama', 'like', "%{$searchValue}%");
                  });
            });
        }

        // Get total count of all records (before filtering)
        $totalRecords = Dosen::count();

        // Get filtered count
        $filteredRecords = $query->count();

        // Add sorting
        if ($orderColumn === 'program_studi') {
            // Handle relationship sorting
            $query->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id')
                  ->orderBy('program_studi.nama', $orderDirection)
                  ->select('dosen.*');
        } else {
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Apply pagination
        $data = $query->offset($start)
                      ->limit($length)
                      ->get()
                      ->map(function ($dosen) {
                          return [
                              'id' => $dosen->id,
                              'nip' => $dosen->nip,
                              'program_studi' => $dosen->programStudi->nama,
                              'nama_dosen' => $dosen->nama,
                              'jenis_kelamin' => $dosen->jenis_kelamin,
                              'telepon' => $dosen->telepon,
                              'email' => $dosen->email,
                              'tanggal_lahir' => $dosen->tanggal_lahir,
                              'jabatan' => $dosen->jabatan,
                              'golongan_akhir' => $dosen->golongan_akhir,
                              'is_wali' => $dosen->is_wali,
                          ];
                      })
                      ->toArray();

        // Return formatted response for DataTables
        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
