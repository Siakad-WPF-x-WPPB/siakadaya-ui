<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
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
        // Ensure the authenticated lecturer is the one teaching this schedule
        if ($jadwal->dosen_id !== Auth::guard('dosen')->id()) {
            abort(403, 'Unauthorized action.');
        }

        // For simplicity with your current schema, let's assume `detail_jadwal` IS the enrollment for that specific schedule instance.
        // If 'detail_jadwal' is meant to store the FRS selections, then the below is correct for your current schema.

        // If you want to list students who have this schedule in their FRS:
        $mahasiswas = \App\Models\Mahasiswa::whereHas('frs.frsDetail', function ($query) use ($jadwal) {
            $query->where('jadwal_id', $jadwal->id)
                ->where('status', 'disetujui'); // Assuming you only want approved students
        })->get();


        return view('pages.dosen.jadwalKuliah.list', compact('jadwal', 'mahasiswas'));
    }
}
