<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Models\ProgramStudi;

class ProgramStudiCollection extends ResourceCollection
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

        // Map column index to actual column name (only available columns)
        $columns = [
            2 => 'kode',
            3 => 'nama',
            4 => 'created_at',
        ];

        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // Get total count first (before any filtering)
        $totalRecords = ProgramStudi::count();

        // Start main query
        $baseQuery = ProgramStudi::query();

        // Clone for filtered count
        $filteredQuery = clone $baseQuery;

        // Apply search if provided (only search in existing fields)
        if (!empty($searchValue)) {
            $searchClosure = function($q) use ($searchValue) {
                $q->where('kode', 'like', "%{$searchValue}%")
                  ->orWhere('nama', 'like', "%{$searchValue}%");
            };

            $baseQuery->where($searchClosure);
            $filteredQuery->where($searchClosure);
        }

        // Get filtered count
        $filteredRecords = $filteredQuery->count();

        // Add sorting (only for existing columns)
        if ($orderColumn === 'kode') {
            $baseQuery->orderBy('kode', $orderDirection);
        } elseif ($orderColumn === 'nama') {
            $baseQuery->orderBy('nama', $orderDirection);
        } elseif ($orderColumn === 'created_at') {
            $baseQuery->orderBy('created_at', $orderDirection);
        } else {
            $baseQuery->orderBy('id', $orderDirection);
        }

        // Apply pagination and get data
        $prodiData = $baseQuery->offset($start)
                               ->limit($length)
                               ->get();

        // Format data (only using existing database fields)
        $data = $prodiData->map(function ($prodi) {
            return [
                'id' => $prodi->id,
                'kode' => $prodi->kode ?? 'No Code',
                'nama' => $prodi->nama ?? 'No Name',

                // For debugging
                'created_at' => $prodi->created_at ? $prodi->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $prodi->updated_at ? $prodi->updated_at->format('Y-m-d H:i:s') : null,
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
