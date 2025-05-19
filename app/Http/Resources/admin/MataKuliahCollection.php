<?php

namespace App\Http\Resources\admin;

use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MataKuliahCollection extends ResourceCollection
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
        $length = $request->get('length', 20); // Number of records per page
        $searchValue = $request->get('search')['value'] ?? ''; // Search value

        // Simulate total records (e.g., from a database query)
        $totalRecords = 20; // Example: Total number of records in the database

        // Simulate filtered records (e.g., based on search functionality)
        $filteredRecords = $totalRecords; // Assume no filtering for now

        // Generate fake data for the current page
        $data = Matakuliah::query()
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(
                function ($matakuliah) {
                    return [
                        'program_studi' => $matakuliah->programStudi->nama,
                        'kode_matakuliah' => $matakuliah->kode,
                        'nama_matakuliah' => $matakuliah->nama,
                        'semester' => $matakuliah->semester,
                        'sks' => $matakuliah->sks,
                        'tipe_matakuliah' => $matakuliah->tipe_matakuliah,
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
