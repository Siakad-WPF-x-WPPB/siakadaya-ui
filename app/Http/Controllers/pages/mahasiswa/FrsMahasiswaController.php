<?php

namespace App\Http\Controllers\pages\mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Resources\mahasiswa\JadwalMahasiswaCollection;
use App\Models\Frs;
use App\Models\FrsDetail;
use App\Models\Jadwal;
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
            'tahun_ajar_id' => 'required|uuid'
        ]);

        $frs = Frs::create([
            'id' => Str::uuid(),
            'mahasiswa_id' => $mahasiswa->id,
            'tahun_ajar_id' => $request->tahun_ajar_id,
            'tanggal_pengisian' => now(),
            'status' => 'pending'
        ]);

        foreach ($request->jadwal_ids as $jadwalId) {
            $detail = FrsDetail::create([
                'id' => Str::uuid(),
                'frs_id' => $frs->id,
                'jadwal_id' => $jadwalId
            ]);

            PersetujuanFrs::create([
                'id' => Str::uuid(),
                'frs_detail_id' => $detail->id,
                'status' => 'pending'
            ]);
        }
    }
}
