<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Models\Ruangan;

class RuanganCollection extends ResourceCollection
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

        // Map column index to actual column name (based on ruangan schema)
        $columns = [
            2 => 'kode',
            3 => 'nama',
            4 => 'gedung',
            5 => 'created_at',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // Get total count first (before any filtering)
        $totalRecords = Ruangan::count();

        // Start main query
        $baseQuery = Ruangan::query();

        // Clone for filtered count
        $filteredQuery = clone $baseQuery;

        // Apply search if provided (search in all ruangan fields)
        if (!empty($searchValue)) {
            $searchClosure = function($q) use ($searchValue) {
                $q->where('kode', 'like', "%{$searchValue}%")
                  ->orWhere('nama', 'like', "%{$searchValue}%")
                  ->orWhere('gedung', 'like', "%{$searchValue}%");
            };

            $baseQuery->where($searchClosure);
            $filteredQuery->where($searchClosure);
        }

        // Get filtered count
        $filteredRecords = $filteredQuery->count();

        // Add sorting (for all ruangan columns)
        if ($orderColumn === 'kode') {
            $baseQuery->orderBy('kode', $orderDirection);
        } elseif ($orderColumn === 'nama') {
            $baseQuery->orderBy('nama', $orderDirection);
        } elseif ($orderColumn === 'gedung') {
            $baseQuery->orderBy('gedung', $orderDirection);
        } elseif ($orderColumn === 'created_at') {
            $baseQuery->orderBy('created_at', $orderDirection);
        } else {
            $baseQuery->orderBy('id', $orderDirection);
        }

        // Apply pagination and get data
        $ruanganData = $baseQuery->offset($start)
                                 ->limit($length)
                                 ->get();

        // Format data (using ruangan database fields)
        $data = $ruanganData->map(function ($ruangan) {
            return [
                'id' => $ruangan->id,
                'kode' => $ruangan->kode ?? 'No Code',
                'nama' => $ruangan->nama ?? 'No Name',
                'gedung' => $ruangan->gedung ?? 'No Building',

                'ruangan_display' => ($ruangan->kode ?? 'No Code') . ' - ' . ($ruangan->nama ?? 'No Name'),
                'location_display' => ($ruangan->nama ?? 'No Name') . ' (' . ($ruangan->gedung ?? 'No Building') . ')',
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
