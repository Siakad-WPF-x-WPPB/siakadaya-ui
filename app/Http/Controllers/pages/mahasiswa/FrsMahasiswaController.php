<?php

namespace App\Http\Controllers\pages\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Frs;
use App\Models\FrsDetail;
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

        $frs = Frs::create([
            'id' => Str::uuid(),
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal_pengisian' => now(),
        ]);

        foreach ($request->jadwal_ids as $jadwalId) {
            FrsDetail::create([
                'id' => Str::uuid(),
                'frs_id' => $frs->id,
                'jadwal_id' => $jadwalId,
                'status' => 'pending',
            ]);
        }
        return response()->json([
            'message' => 'Data FRS berhasil disimpan',
            'data' => $frs,
        ], 201);
    }
}
