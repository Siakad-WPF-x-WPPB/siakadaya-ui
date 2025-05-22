<?php

namespace App\Http\Resources\dosen;

use App\Models\Frs;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FrsCollection extends ResourceCollection
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
        $data = Frs::query()
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(
                function ($frs) {
                    return [
                        'nama_mahasiswa' => $frs->mahasiswa->nama,
                        'matakuliah' => $frs->jadwal->matakuliah->nama,
                        'sks' => $frs->jadwal->matakuliah->sks,
                        'tanggal_pengisian' => $frs->tanggal_pengisian,
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
