<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Models\Frs;
use App\Models\FrsDetail;
use App\Models\Jadwal;
use App\Models\PersetujuanFrs;
use Illuminate\Http\Request;

class DosenFrsController extends Controller
{
    public function index()
    {
        return view('pages.dosen.frs.index');
    }

    public function show($id)
    {
        $frs = Frs::with(['mahasiswa', 'frsDetail.jadwal.matakuliah'])->findOrFail($id);
        return view('pages.dosen.frs.show', compact('frs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak'
        ]);

        $persetujuan = FrsDetail::findOrFail($id);
        $persetujuan->update([
            'status' => $request->status,
            'tanggal_persetujuan' => now()
        ]);

        return back()->with('success', 'Status persetujuan diperbarui.');
    }
}
