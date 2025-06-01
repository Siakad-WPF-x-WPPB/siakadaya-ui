<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\admin\PengumumanCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengumuman;

class MahasiswaPengumumanController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $pengumuman = Pengumuman::where('status', 'aktif')
            ->orderBy('tanggal_dibuat', 'desc')
            ->get();
        if ($pengumuman->isEmpty())
        {
            return response()->json(['data' => [], 'message' => 'Tidak ada pengumuman ditemukan.'], 200);
        }

        return new PengumumanCollection($pengumuman);
    }
}