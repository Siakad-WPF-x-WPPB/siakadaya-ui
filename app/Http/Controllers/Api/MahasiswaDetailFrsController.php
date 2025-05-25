<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\mahasiswa\DetailFrsMahasiswaCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FrsDetail;

class MahasiswaDetailFrsController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $frsDetails = FrsDetail::whereHas('frs', function ($query) use ($mahasiswa) {
            $query->where('mahasiswa_id', $mahasiswa->id);
        })
            ->with([
                'frs.tahunAjar',
                'jadwal.matakuliah',
                'jadwal.dosen',
                'jadwal.kelas',
                'jadwal.ruangan'
            ])
            ->get();

        if ($frsDetails->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'Tidak ada detail FRS ditemukan.'], 200);
        }

        return new DetailFrsMahasiswaCollection($frsDetails);
    }
}
