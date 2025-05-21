<?php

namespace App\Http\Controllers\pages\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalKuliahMahasiswaController extends Controller
{
    /**
     * Display all class schedules for the student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // enyesuaikan query ini berdasarkan relasi model 
        $kelas_id = $request->user()->kelas_id ?? $request->kelas_id;
        
        $jadwal = Jadwal::when($kelas_id, function($query) use ($kelas_id) {
            return $query->where('kelas_id', $kelas_id);
        })
        ->with(['kelas', 'dosen', 'matakuliah', 'ruangan'])
        ->orderBy('hari')
        ->orderBy('jam_mulai')
        ->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Daftar jadwal kuliah berhasil diambil',
            'data' => $jadwal
        ]);
    }

    /**
     * Display today's class schedules
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hariIni(Request $request)
    {
        $kelas_id = $request->user()->kelas_id ?? $request->kelas_id;
        $hariIni = Carbon::now()->locale('id')->dayName;
        
        $jadwal = Jadwal::when($kelas_id, function($query) use ($kelas_id) {
            return $query->where('kelas_id', $kelas_id);
        })
        ->where('hari', $hariIni)
        ->with(['kelas', 'dosen', 'matakuliah', 'ruangan'])
        ->orderBy('jam_mulai')
        ->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal kuliah hari ini berhasil diambil',
            'data' => $jadwal
        ]);
    }
    
    /**
     * Display tomorrow's class schedules
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function besok(Request $request)
    {
        $kelas_id = $request->user()->kelas_id ?? $request->kelas_id;
        $besok = Carbon::tomorrow()->locale('id')->dayName;
        
        $jadwal = Jadwal::when($kelas_id, function($query) use ($kelas_id) {
            return $query->where('kelas_id', $kelas_id);
        })
        ->where('hari', $besok)
        ->with(['kelas', 'dosen', 'matakuliah', 'ruangan'])
        ->orderBy('jam_mulai')
        ->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal kuliah besok berhasil diambil',
            'data' => $jadwal
        ]);
    }
    
    /**
     * Display schedule details for a specific class
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $jadwal = Jadwal::with(['kelas', 'dosen', 'matakuliah', 'ruangan'])
            ->findOrFail($id);
            
        return response()->json([
            'status' => 'success',
            'message' => 'Detail jadwal kuliah berhasil diambil',
            'data' => $jadwal
        ]);
    }
}