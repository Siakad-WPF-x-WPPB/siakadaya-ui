<?php

namespace App\Http\Resources\admin;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MahasiswaCollection extends ResourceCollection
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

    // Get filter parameters
    $prodiFilter = $request->get('prodi_filter');
    $kelasFilter = $request->get('kelas_filter');
    $statusFilter = $request->get('status_filter');
    $semesterFilter = $request->get('semester_filter');
    $genderFilter = $request->get('gender_filter');
    $yearFilter = $request->get('year_filter');

    // Get sort parameters
    $orderColumnIndex = $request->get('order')[0]['column'] ?? 2;
    $orderDirection = $request->get('order')[0]['dir'] ?? 'asc';

    // Map column index to actual column name
    $columns = [
        0 => '',
        1 => 'id',
        2 => 'nrp',
        3 => 'nama',
        4 => 'program_studi',
        5 => 'kelas',
        6 => 'jenis_kelamin',
        7 => 'status',
        8 => ''
    ];

    $orderColumn = $columns[$orderColumnIndex] ?? 'nrp';

    // Start query
    $query = Mahasiswa::query();

    // Apply filters
    if (!empty($prodiFilter)) {
        $query->where('prodi_id', $prodiFilter);
    }

    if (!empty($kelasFilter)) {
        $query->where('kelas_id', $kelasFilter);
    }

    if (!empty($statusFilter)) {
        $query->where('status', $statusFilter);
    }

    if (!empty($semesterFilter)) {
        $query->where('semester', $semesterFilter);
    }

    if (!empty($genderFilter)) {
        $query->where('jenis_kelamin', $genderFilter);
    }

    if (!empty($yearFilter)) {
        $query->whereYear('tanggal_masuk', $yearFilter);
    }

    // Apply search
    if (!empty($searchValue)) {
        $query->where(function($q) use ($searchValue) {
            $q->where('nrp', 'like', "%{$searchValue}%")
              ->orWhere('nama', 'like', "%{$searchValue}%")
              ->orWhere('email', 'like', "%{$searchValue}%")
              ->orWhere('status', 'like', "%{$searchValue}%")
              ->orWhereHas('programStudi', function($q) use ($searchValue) {
                  $q->where('nama', 'like', "%{$searchValue}%");
              })
              ->orWhereHas('kelas', function($q) use ($searchValue) {
                  $q->where('pararel', 'like', "%{$searchValue}%");
              });
        });
    }

    // Get total count of all records (before filtering)
    $totalRecords = Mahasiswa::count();

    // Get filtered count
    $filteredRecords = $query->count();

    // Add sorting
    if ($orderColumn === 'program_studi') {
        $query->join('program_studi', 'mahasiswa.prodi_id', '=', 'program_studi.id')
              ->orderBy('program_studi.nama', $orderDirection)
              ->select('mahasiswa.*');
    } elseif ($orderColumn === 'kelas') {
        $query->join('kelas', 'mahasiswa.kelas_id', '=', 'kelas.id')
              ->orderBy('kelas.pararel', $orderDirection)
              ->select('mahasiswa.*');
    } else {
      $query->orderBy('mahasiswa.' . $orderColumn, $orderDirection);
    }

    // Apply pagination
    $data = $query->with(['programStudi', 'kelas'])
                  ->offset($start)
                  ->limit($length)
                  ->get()
                  ->map(function ($mahasiswa) {
                      return [
                        'id' => $mahasiswa->id,
                        'program_studi' => $mahasiswa->programStudi->nama,
                        'kelas' => $mahasiswa->kelas->pararel,
                        'nrp' => $mahasiswa->nrp,
                        'nama_mahasiswa' => $mahasiswa->nama,
                        'jenis_kelamin' => $mahasiswa->jenis_kelamin,
                        'telepon' => $mahasiswa->telepon,
                        'email' => $mahasiswa->email,
                        'agama' => $mahasiswa->agama,
                        'semester' => $mahasiswa->semester,
                        'tanggal_lahir' => $mahasiswa->tanggal_lahir,
                        'tanggal_masuk' => $mahasiswa->tanggal_masuk,
                        'status' => $mahasiswa->status,
                        'alamat_jalan' => $mahasiswa->alamat_jalan,
                        'provinsi' => $mahasiswa->provinsi,
                        'kode_pos' => $mahasiswa->kode_pos,
                        'negara' => $mahasiswa->negara,
                        'kelurahan' => $mahasiswa->kelurahan,
                        'kecamatan' => $mahasiswa->kecamatan,
                        'kota' => $mahasiswa->kota
                      ];
                  })
                  ->toArray();

    // Get filter options for dropdowns
    $filterOptions = [
        'program_studi' => ProgramStudi::select('id', 'nama')->get(),
        'kelas' => $prodiFilter ?
            Kelas::where('prodi_id', $prodiFilter)->select('id', 'pararel')->get() :
            Kelas::select('id', 'pararel')->get(),
        'status' => [
            ['value' => 'Aktif', 'label' => 'Aktif'],
            ['value' => 'Cuti', 'label' => 'Cuti'],
            ['value' => 'Keluar', 'label' => 'Keluar']
        ],
        'years' => Mahasiswa::selectRaw('YEAR(tanggal_masuk) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter()
            ->map(function ($year) {
                return ['value' => $year, 'label' => $year];
            })
            ->values()
            ->toArray()
    ];

    // Return formatted response for DataTables
    return [
      'draw' => intval($draw),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $filteredRecords,
      'data' => $data,
      'filterOptions' => $filterOptions,
    ];
  }
}
