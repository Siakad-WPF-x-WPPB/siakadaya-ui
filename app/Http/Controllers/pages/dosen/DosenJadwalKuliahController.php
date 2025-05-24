<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenJadwalKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated lecturer's ID
        $dosenId = Auth::guard('dosen')->id();

        // Fetch schedules taught by this lecturer
        // Eager load related data you might need in the view (e.g., mata kuliah, kelas, ruangan)
        $jadwals = Jadwal::where('dosen_id', $dosenId)
            ->with(['matakuliah', 'kelas', 'ruangan']) // Add other relations if needed
            ->orderBy('hari') // Optional: order by day
            ->orderBy('jam_mulai') // Optional: then by start time
            ->get();

        return view('pages.dosen.jadwalKuliah.index', compact('jadwals'));
    }

    public function listMahasiswa(Jadwal $jadwal)
    {
        if ($jadwal->dosen_id !== Auth::guard('dosen')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $mahasiswas = Mahasiswa::whereHas('frs.frsDetail', function ($query) use ($jadwal) {
            $query->where('jadwal_id', $jadwal->id)
                ->where('status', 'disetujui');
        })->get();


        return view('pages.dosen.jadwalKuliah.list', compact('jadwal', 'mahasiswas'));
    }
}
