<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use App\Models\TahunAjar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::count();
        $dosen = Dosen::count();
        $kelas = Kelas::count();
        $programStudi = ProgramStudi::count();
        $tahunAjar = TahunAjar::count();
        $ruangan = Ruangan::count();
        $matakuliah = Matakuliah::count();
        $jadwal = Jadwal::count();
        
        return view('pages.admin.dashboard.index', [
            'mahasiswa' => $mahasiswa,
            'dosen' => $dosen,
            'kelas' => $kelas,
            'programStudi' => $programStudi,
            'tahunAjar' => $tahunAjar,
            'ruangan' => $ruangan,
            'matakuliah' => $matakuliah,
            'jadwal' => $jadwal
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
