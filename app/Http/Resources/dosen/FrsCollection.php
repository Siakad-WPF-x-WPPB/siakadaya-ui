<?php

namespace App\Http\Resources\dosen;

use App\Models\Frs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FrsCollection extends ResourceCollection
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

        $dosen = Auth::guard('dosen')->user();

        // Debug: Log current dosen information
        Log::info('FRS Debug - Current Dosen:', [
            'dosen_id' => $dosen->id,
            'dosen_name' => $dosen->nama,
            'is_wali' => $dosen->is_wali
        ]);

        // Get total records for this dosen wali only
        $totalRecords = Frs::whereHas('mahasiswa.kelas', function ($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id); // Changed from dosen_wali_id to dosen_id
        })->count();

        // Debug: Log total records found
        Log::info('FRS Debug - Total Records Found:', ['total' => $totalRecords]);

        // Apply search and count filtered records
        $query = Frs::whereHas('mahasiswa.kelas', function ($q) use ($dosen) {
            $q->where('dosen_id', $dosen->id); // Changed from dosen_wali_id to dosen_id
        });

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->whereHas('mahasiswa', function ($mq) use ($searchValue) {
                    $mq->where('nama', 'like', "%{$searchValue}%")
                      ->orWhere('nrp', 'like', "%{$searchValue}%");
                });
            });
        }

        $filteredRecords = $query->count();

        // Transform the collection data
        $data = $this->collection->map(function ($frs) use ($dosen) {
            // Debug: Log each FRS data
            Log::info('FRS Debug - Processing FRS:', [
                'frs_id' => $frs->id,
                'mahasiswa_id' => $frs->mahasiswa_id,
                'mahasiswa_name' => $frs->mahasiswa->nama ?? 'N/A',
                'mahasiswa_nrp' => $frs->mahasiswa->nrp ?? 'N/A'
            ]);

            // Get kelas information with detailed debugging
            $kelas = $frs->mahasiswa->kelas ?? null;
            $programStudi = $kelas->programStudi ?? null;
            $dosenWali = $kelas->dosen ?? null; // Changed from dosenWali to dosen

            // Debug: Log kelas and dosen information
            Log::info('FRS Debug - Kelas Info:', [
                'kelas_id' => $kelas->id ?? 'N/A',
                'kelas_name' => $kelas->nama ?? 'N/A',
                'dosen_id' => $kelas->dosen_id ?? 'N/A',
                'dosen_wali_name' => $dosenWali->nama ?? 'N/A',
                'program_studi_id' => $kelas->prodi_id ?? 'N/A',
                'program_studi_name' => $programStudi->nama ?? 'N/A',
                'current_dosen_id' => $dosen->id,
                'is_match' => ($kelas->dosen_id ?? null) == $dosen->id
            ]);

            // Calculate total mata kuliah and status summary
            $totalMatakuliah = $frs->frsDetail->count();
            $disetujui = $frs->frsDetail->where('status', 'disetujui')->count();
            $ditolak = $frs->frsDetail->where('status', 'ditolak')->count();
            $menunggu = $frs->frsDetail->where('status', 'pending')->count(); // Changed from 'menunggu' to 'pending'

            // Fix date formatting - handle both string and Carbon instances
            $tanggalPengisian = $frs->tanggal_pengisian;
            if (is_string($tanggalPengisian)) {
                $tanggalPengisian = Carbon::parse($tanggalPengisian);
            }

            return [
                'id' => $frs->id,
                'nama_mahasiswa' => $frs->mahasiswa->nama,
                'nrp' => $frs->mahasiswa->nrp,
                'kelas' => $kelas->pararel ?? 'N/A', // Changed from nama to pararel based on your migration
                'kelas_id' => $kelas->id ?? 'N/A',
                'program_studi' => $programStudi->nama ?? 'N/A',
                'program_studi_id' => $programStudi->id ?? 'N/A',
                'dosen_wali' => $dosenWali->nama ?? 'N/A',
                'dosen_id' => $dosenWali->id ?? 'N/A',
                'tanggal_pengisian' => $tanggalPengisian->format('d/m/Y H:i'),
                'total_matakuliah' => $totalMatakuliah,
                'status_summary' => [
                    'disetujui' => $disetujui,
                    'ditolak' => $ditolak,
                    'menunggu' => $menunggu
                ],
                'status_badge' => $this->getStatusBadge($disetujui, $ditolak, $menunggu, $totalMatakuliah)
            ];
        })->toArray();

        // Debug: Log final data count
        Log::info('FRS Debug - Final Data:', [
            'data_count' => count($data),
            'filtered_records' => $filteredRecords
        ]);

        return [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }

    private function getStatusBadge($disetujui, $ditolak, $menunggu, $total)
    {
        if ($menunggu > 0) {
            return '<span class="badge bg-warning">Menunggu Persetujuan</span>';
        } elseif ($disetujui == $total) {
            return '<span class="badge bg-success">Semua Disetujui</span>';
        } elseif ($ditolak > 0) {
            return '<span class="badge bg-danger">Ada yang Ditolak</span>';
        } else {
            return '<span class="badge bg-secondary">-</span>';
        }
    }
}
