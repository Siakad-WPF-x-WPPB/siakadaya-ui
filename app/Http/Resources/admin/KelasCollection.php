<?php

namespace App\Http\Resources\admin;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class KelasCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $draw = $request->get('draw', 1); // DataTables draw counter
        $start = $request->get('start', 0); // Starting record index
        $length = $request->get('length', 10); // Number of records per page
        $searchValue = $request->get('search')['value'] ?? ''; // Search value

        // Simulate total records (e.g., from a database query)
        $totalRecords = 10; // Example: Total number of records in the database

        // Simulate filtered records (e.g., based on search functionality)
        $filteredRecords = $totalRecords; // Assume no filtering for now

        // Generate fake data for the current page
        $data = Kelas::with('dosen')
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(
                function ($kelas) {
                    return [
                        'nama_kelas' => $kelas->nama_kelas,
                        'nama_dosen' => $kelas->dosen->nama_dosen,
                    ];
                }
            )
            ->toArray();

        return [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
