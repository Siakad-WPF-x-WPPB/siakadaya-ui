<?php

namespace App\Http\Controllers\pages\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Frs;
use App\Models\FrsDetail;
use App\Models\Jadwal;
use App\Models\TahunAjar;
use App\Models\PersetujuanFrs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FrsMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $request->validate([
            'jadwal_ids' => 'required|array',
        ]);

        $existingJadwalIds = FrsDetail::whereHas('frs', function ($query) use ($mahasiswa) {
            $query->where('mahasiswa_id', $mahasiswa->id);
        })->pluck('jadwal_id')->toArray();

        $duplicateJadwal = array_intersect($request->jadwal_ids, $existingJadwalIds);
        if (!empty($duplicateJadwal)) {
            return response()->json([
                'message' => 'Anda sudah mengambil salah satu jadwal yang dipilih.',
                'jadwal_ids' => array_values($duplicateJadwal),
            ], 422);
        }

        // Get active tahun ajar
        $activeTahunAjar = TahunAjar::where('status', 'Aktif')->first();

        if (!$activeTahunAjar) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tahun ajar yang aktif'
            ], 404);
        }

        // Check if FRS period is open
        // if (!$activeTahunAjar->isFrsOpen()) {
        //     $status = $activeTahunAjar->getFrsStatus();
        //     $message = match($status) {
        //         'not_started' => 'Periode FRS belum dimulai. Dimulai tanggal ' . $activeTahunAjar->mulai_frs?->format('d M Y'),
        //         'edit_open' => 'Periode FRS sudah berakhir. Saat ini periode edit FRS.',
        //         'drop_open' => 'Periode FRS sudah berakhir. Saat ini periode drop FRS.',
        //         'closed' => 'Periode FRS sudah berakhir.',
        //         default => 'Periode FRS tidak aktif.'
        //     };

        //     return response()->json([
        //         'success' => false,
        //         'message' => $message,
        //         'frs_status' => $status
        //     ], 422);
        // }

        // Check if student already has FRS for this active academic year
        // $existingFrs = Frs::where('mahasiswa_id', $mahasiswa->id)
        //                   ->where('tahun_ajar_id', $activeTahunAjar->id)
        //                   ->first();

        // if ($existingFrs) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Anda sudah mengisi FRS untuk tahun ajar yang aktif.'
        //     ], 422);
        // }

        // Validate that all jadwal belong to active tahun ajar and student's class
        $jadwalIds = $request->jadwal_ids;
        $validJadwal = Jadwal::whereIn('id', $jadwalIds)
                            ->where('tahun_ajar_id', $activeTahunAjar->id)
                            ->whereHas('kelas.mahasiswa', function ($q) use ($mahasiswa) {
                                $q->where('id', $mahasiswa->id);
                            })
                            ->pluck('id')
                            ->toArray();

        if (count($validJadwal) !== count($jadwalIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Beberapa jadwal tidak valid atau tidak tersedia untuk Anda.'
            ], 422);
        }

        // Create FRS record
        $frs = Frs::create([
            'id' => Str::uuid(),
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_ajar_id' => $activeTahunAjar->id,
            'tanggal_pengisian' => now(),
        ]);

        // Create FRS details
        foreach ($jadwalIds as $jadwalId) {
            FrsDetail::create([
                'id' => Str::uuid(),
                'frs_id' => $frs->id,
                'jadwal_id' => $jadwalId,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data FRS berhasil disimpan',
            'data' => $frs->load(['details.jadwal', 'tahunAjar']),
        ], 201);
    }

    public function getMyFrs(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        // Get active tahun ajar
        $activeTahunAjar = TahunAjar::where('status', 'Aktif')->first();

        if (!$activeTahunAjar) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tahun ajar yang aktif'
            ], 404);
        }

        // Get FRS for active tahun ajar
        $frs = Frs::where('mahasiswa_id', $mahasiswa->id)
                  ->where('tahun_ajar_id', $activeTahunAjar->id)
                  ->with([
                      'details' => function ($query) {
                          $query->where('status', 'disetujui')
                                ->with([
                                    'jadwal.dosen',
                                    'jadwal.matakuliah',
                                    'jadwal.kelas',
                                    'jadwal.ruangan'
                                ]);
                      },
                      'tahunAjar'
                  ])
                  ->first();

        if (!$frs || $frs->details->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada FRS yang disetujui untuk tahun ajar yang aktif'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $frs,
            'tahun_ajar' => [
                'id' => $activeTahunAjar->id,
                'semester' => $activeTahunAjar->semester,
                'tahun_mulai' => $activeTahunAjar->tahun_mulai,
                'tahun_akhir' => $activeTahunAjar->tahun_akhir,
                'display_name' => $activeTahunAjar->semester . ' ' . $activeTahunAjar->tahun_mulai . '/' . $activeTahunAjar->tahun_akhir,
                'frs_status' => $activeTahunAjar->getFrsStatus()
            ]
        ]);
    }

    public function checkFrsStatus()
    {
        $tahunAjar = TahunAjar::where('status', 'Aktif')->first();

        if (!$tahunAjar) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada tahun ajar yang aktif',
                'status' => 'no_active_year'
            ]);
        }

        $status = $tahunAjar->getFrsStatus();

        return response()->json([
            'success' => true,
            'tahun_ajar' => [
                'id' => $tahunAjar->id,
                'semester' => $tahunAjar->semester,
                'tahun_mulai' => $tahunAjar->tahun_mulai,
                'tahun_akhir' => $tahunAjar->tahun_akhir,
                'display_name' => $tahunAjar->semester . ' ' . $tahunAjar->tahun_mulai . '/' . $tahunAjar->tahun_akhir,
            ],
            'frs_status' => $status,
            'is_frs_open' => $tahunAjar->isFrsOpen(),
            'is_edit_open' => $tahunAjar->isFrsEditOpen(),
            'is_drop_open' => $tahunAjar->isFrsDropOpen(),
            'periods' => [
                'mulai_frs' => $tahunAjar->mulai_frs?->format('Y-m-d'),
                'selesai_frs' => $tahunAjar->selesai_frs?->format('Y-m-d'),
                'mulai_edit_frs' => $tahunAjar->mulai_edit_frs?->format('Y-m-d'),
                'selesai_edit_frs' => $tahunAjar->selesai_edit_frs?->format('Y-m-d'),
                'mulai_drop_frs' => $tahunAjar->mulai_drop_frs?->format('Y-m-d'),
                'selesai_drop_frs' => $tahunAjar->selesai_drop_frs?->format('Y-m-d'),
            ]
        ]);
    }
}
