<?php

namespace App\Http\Resources\admin;

use App\Models\Mahasiswa;
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
    $data = Mahasiswa::query()
      ->offset($start)
      ->limit($length)
      ->get()
      ->map(
        function ($mahasiswa) {
          return [
            'nrp' => $mahasiswa->nrp,
            'kode_jurusan' => $mahasiswa->kode_jurusan,
            'id_kelas' => $mahasiswa->id_kelas,
            'nama' => $mahasiswa->nama_mahasiswa,
            'jenis_kelamin' => $mahasiswa->jenis_kelamin,
            'telepon' => $mahasiswa->telepon,
            'email' => $mahasiswa->email,
            'tanggal_lahir' => $mahasiswa->tanggal_lahir,
            'tanggal_masuk' => $mahasiswa->tanggal_masuk,
            'status' => $mahasiswa->status,
            'alamat_jalan' => $mahasiswa->alamat,

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
