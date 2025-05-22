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
        // Get DataTables parameters
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $searchValue = $request->get('search')['value'] ?? '';

        // Get sort parameters
        $orderColumnIndex = $request->get('order')[0]['column'] ?? 2;
        $orderDirection = $request->get('order')[0]['dir'] ?? 'asc';

        // Map column index to actual column name
        $columns = [
            2 => 'pararel',
            3 => 'nama_dosen',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'pararel';

        // Start query
        $query = Kelas::query();

        // Apply search if provided
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('pararel', 'like', "%{$searchValue}%")
                  ->orWhereHas('dosen', function($q) use ($searchValue) {
                      $q->where('nama', 'like', "%{$searchValue}%")
                        ->orWhere('nip', 'like', "%{$searchValue}%");
                  });
            });
        }

        // Get total count of all records (before filtering)
        $totalRecords = Kelas::count();

        // Get filtered count
        $filteredRecords = $query->count();

        // Add sorting
        if ($orderColumn === 'nama_dosen') {
            // Handle relationship sorting
            $query->join('dosen', 'kelas.dosen_id', '=', 'dosen.id')
                  ->orderBy('dosen.nama', $orderDirection)
                  ->select('kelas.*');
        } else {
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Apply pagination
        $data = $query->with(['dosen']) // Eager load relationships
                      ->offset($start)
                      ->limit($length)
                      ->get()
                      ->map(function ($kelas) {
                          return [
                              'id' => $kelas->id,
                              'pararel' => $kelas->pararel,
                              'dosen_id' => $kelas->dosen_id,
                              'nama_dosen' => $kelas->dosen ? $kelas->dosen->nama : 'No Dosen Assigned',
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
