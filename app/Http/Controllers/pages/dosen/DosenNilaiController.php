<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Models\FrsDetail;
use App\Models\Jadwal;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Nilai;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DosenNilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create(Jadwal $jadwal, Mahasiswa $mahasiswa)
    {
        // Authorization: Ensure the lecturer teaches this schedule
        if ($jadwal->dosen_id !== Auth::guard('dosen')->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get frsDetail for this mahasiswa and jadwal
        $frsDetail = FrsDetail::where('jadwal_id', $jadwal->id)
            ->whereHas('frs', function ($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->where('status', 'disetujui')
            ->with('frs')
            ->first();

        if (!$frsDetail) {
            return redirect()->route('dosen.jadwal.mahasiswa', $jadwal->id)
                ->with('error', 'Data frs tidak ditemukan untuk mahasiswa pada jadwal ini.');
        }

        // Find existing nilai or create a new one for the form (by frs_detail_id only)
        $nilai = Nilai::firstOrNew([
            'frs_detail_id' => $frsDetail->id,
        ]);

        return view('pages.dosen.nilai.form', compact('jadwal', 'mahasiswa', 'nilai'));
    }

    private function calculateGradeDetails(int $nilaiAngka): array
    {
        $nilaiHuruf = 'E';
        $status = 'tidak lulus';

        if ($nilaiAngka >= 85) $nilaiHuruf = 'A';
        elseif ($nilaiAngka >= 80) $nilaiHuruf = 'AB';
        elseif ($nilaiAngka >= 75) $nilaiHuruf = 'B';
        elseif ($nilaiAngka >= 70) $nilaiHuruf = 'BC';
        elseif ($nilaiAngka >= 60) $nilaiHuruf = 'C';
        elseif ($nilaiAngka >= 50) $nilaiHuruf = 'D';

        if (in_array($nilaiHuruf, ['A', 'AB', 'B', 'BC', 'C'])) {
            $status = 'lulus';
        }

        return ['nilai_huruf' => $nilaiHuruf, 'status' => $status];
    }

    public function store(Request $request, Jadwal $jadwal, Mahasiswa $mahasiswa)
    {
        // Authorization
        if ($jadwal->dosen_id !== Auth::guard('dosen')->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get frsDetail for this mahasiswa and jadwal
        $frsDetail = FrsDetail::where('jadwal_id', $jadwal->id)
            ->whereHas('frs', function ($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->where('status', 'disetujui')
            ->with('frs')
            ->first();

        if (!$frsDetail) {
            return redirect()->route('dosen.jadwal.mahasiswa', $jadwal->id)
                ->with('error', 'Gagal menyimpan nilai. Data frs tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nilai_angka' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $nilaiAngka = (int) $request->nilai_angka;
        $gradeDetails = $this->calculateGradeDetails($nilaiAngka);

        Nilai::updateOrCreate(
            [
                'frs_detail_id' => $frsDetail->id,
                'frs_detail_id' => $frsDetail->id,
            ],
            [
                'nilai_angka' => $nilaiAngka,
                'nilai_huruf' => $gradeDetails['nilai_huruf'],
                'status' => $gradeDetails['status'],
            ]
        );

        return redirect()->route('dosen.jadwal.mahasiswa', $jadwal->id)
            ->with('success', 'Nilai untuk mahasiswa ' . ($mahasiswa->nama ?? $mahasiswa->id) . ' berhasil disimpan.');
    }
}
