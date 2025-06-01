<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\mahasiswa\DetailFrsMahasiswaCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FrsDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function destroy($id)
    {
        try {
            $mahasiswa = Auth::guard('mahasiswa_api')->user();

            if (!$mahasiswa) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            $frsDetail = FrsDetail::whereHas('frs', function ($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })->find($id);

            if (!$frsDetail) {
                return response()->json(['message' => 'Detail FRS tidak ditemukan atau tidak memiliki akses.'], 404);
            }

            DB::beginTransaction();

            $frsDetail->delete();

            DB::commit();

            return response()->json([
                'message' => 'Mata kuliah berhasil dihapus dari FRS.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting FRS detail: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus mata kuliah dari FRS.'
            ], 500);
        }
    }
}
