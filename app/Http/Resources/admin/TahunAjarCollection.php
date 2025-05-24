<?php

namespace App\Http\Resources\admin;

use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TahunAjarCollection extends ResourceCollection
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
        $length = $request->get('length', 20);
        $searchValue = $request->get('search')['value'] ?? '';

        // Get sort parameters
        $orderColumnIndex = $request->get('order')[0]['column'] ?? 3; // Default to tahun_ajaran column
        $orderDirection = $request->get('order')[0]['dir'] ?? 'desc'; // Default to newest first

        // Map column index to actual column name
        $columns = [
            2 => 'semester',
            3 => 'tahun_mulai', // Sort by tahun_mulai for tahun_ajaran
            4 => 'status',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'tahun_mulai';

        // Start query
        $query = TahunAjar::query();

        // Apply search if provided
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('semester', 'like', "%{$searchValue}%")
                  ->orWhere('tahun_mulai', 'like', "%{$searchValue}%")
                  ->orWhere('tahun_akhir', 'like', "%{$searchValue}%")
                  ->orWhere('status', 'like', "%{$searchValue}%")
                  // Search in combined tahun_ajaran format
                  ->orWhereRaw("CONCAT(tahun_mulai, '/', tahun_akhir) LIKE ?", ["%{$searchValue}%"]);
            });
        }

        // Get total count of all records (before filtering)
        $totalRecords = TahunAjar::count();

        // Get filtered count
        $filteredRecords = $query->count();

        // Apply sorting - for newest tahun ajaran, sort by tahun_mulai descending by default
        if ($orderColumn === 'tahun_mulai') {
            $query->orderBy('tahun_mulai', $orderDirection)
                  ->orderBy('tahun_akhir', $orderDirection);
        } else {
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Apply pagination and get data
        $data = $query->offset($start)
                      ->limit($length)
                      ->get()
                      ->map(function ($tahunAjar) {
                          return [
                              'id' => $tahunAjar->id,
                              'semester' => $tahunAjar->semester,
                              'tahun_ajaran' => $tahunAjar->tahun_mulai . '/' . $tahunAjar->tahun_akhir,
                              'tahun_mulai' => $tahunAjar->tahun_mulai, // Add for sorting reference
                              'tahun_akhir' => $tahunAjar->tahun_akhir, // Add for sorting reference
                              'status' => $tahunAjar->status,
                          ];
                      })
                      ->toArray();

        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
