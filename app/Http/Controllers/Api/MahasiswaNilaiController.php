<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\mahasiswa\DetailFrsMahasiswaCollection;
use App\Http\Resources\mahasiswa\NilaiMahasiswaCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FrsDetail;
use App\Models\Nilai;

class MahasiswaNilaiController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $nilai = Nilai::whereHas('frsDetail.frs', function ($query) use ($mahasiswa) {
            $query->where('mahasiswa_id', $mahasiswa->id);
        })->get();

        if ($nilai->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'Tidak ada nilai ditemukan.'], 200);
        }

        return new NilaiMahasiswaCollection($nilai);
    }
}
