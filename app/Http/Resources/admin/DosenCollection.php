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
        // Simulate server-side processing
        $draw = $request->get('draw', 1); // DataTables draw counter
        $start = $request->get('start', 0); // Starting record index
        $length = $request->get('length', 10); // Number of records per page
        $searchValue = $request->get('search')['value'] ?? ''; // Search value

        // Simulate total records (e.g., from a database query)
        $totalRecords = 10; // Example: Total number of records in the database

        // Simulate filtered records (e.g., based on search functionality)
        $filteredRecords = $totalRecords; // Assume no filtering for now

        // Generate fake data for the current page
        $data = Dosen::query()
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(
                function ($dosen) {
                    return [
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
                }
            )
            ->toArray();

        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
