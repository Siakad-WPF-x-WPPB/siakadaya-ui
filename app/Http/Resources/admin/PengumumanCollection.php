<?php

namespace App\Http\Resources\admin;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PengumumanCollection extends ResourceCollection
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
        $data = Pengumuman::query()
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(
                function ($pengumuman) {
                    return [
                        'id' => $pengumuman->id,
                        'nama_pembuat' => $pengumuman->admin->nama,
                        'judul' => $pengumuman->judul,
                        'isi' => $pengumuman->isi,
                        'tanggal_dibuat' => $pengumuman->tanggal_dibuat,
                        'status' => $pengumuman->status,
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
