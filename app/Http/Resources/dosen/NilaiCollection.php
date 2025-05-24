<?php

namespace App\Http\Resources\dosen;

use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NilaiCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $draw = $request->get('draw', 1);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $searchValue = $request->get('search')['value'] ?? '';

        // Dapatkan ID dosen yang sedang login
        $dosenId = Auth::guard('dosen')->id();
        Log::info('Dosen ID in NilaiCollection: ' . $dosenId); // Tambahkan ini

        if (!$dosenId) {
            Log::error('Auth::guard(\'dosen\')->id() is null in NilaiCollection. No data will be fetched for dosen.');
            // ...
        }
        // Bangun query dasar dengan filter dosen_id
        $query = Nilai::query()->where('dosen_id', $dosenId);

        // Handle pencarian jika ada
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->whereHas('mahasiswa', function ($subQ) use ($searchValue) {
                    $subQ->where('nama', 'like', '%' . $searchValue . '%');
                })
                    ->orWhereHas('matakuliah', function ($subQ) use ($searchValue) {
                        $subQ->where('nama', 'like', '%' . $searchValue . '%');
                    })
                    ->orWhere('status', 'like', '%' . $searchValue . '%')
                    ->orWhere('nilai_huruf', 'like', '%' . $searchValue . '%')
                    ->orWhere('nilai_angka', 'like', '%' . $searchValue . '%');
            });
        }

        $totalRecords = (clone $query)->count(); // Clone query untuk menghitung total sebelum pagination
        $filteredRecords = $totalRecords; // filteredRecords akan sama dengan totalRecords jika tidak ada pencarian, atau dihitung setelah pencarian

        $data = $query
            ->with(['dosen', 'mahasiswa', 'matakuliah']) // Eager load relasi untuk efisiensi
            ->offset($start)
            ->limit($length)
            ->get()
            ->map(
                function ($nilai) {
                    return [
                        'id' => $nilai->id,
                        'dosen_id' => $nilai->dosen->nama, // Sebaiknya tetap tampilkan nama dosen jika perlu, atau hapus jika tidak
                        'nama_mahasiswa' => $nilai->mahasiswa->nama,
                        'nama_matakuliah' => $nilai->matakuliah->nama,
                        'status' => $nilai->status,
                        'nilai_huruf' => $nilai->nilai_huruf,
                        'nilai_angka' => $nilai->nilai_angka,
                        // Anda mungkin ingin menambahkan kolom 'tanggal_pengisian' dari timestamps jika diperlukan oleh DataTables
                        // 'tanggal_pengisian' => $nilai->created_at->format('Y-m-d'), // Contoh format
                    ];
                }
            )
            ->toArray();

        return [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords, // Pastikan ini dihitung dengan benar jika ada pencarian
            'data' => $data,
        ];
    }
}
