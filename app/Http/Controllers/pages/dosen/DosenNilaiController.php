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

        // Get tahun_ajar_id from the student's approved FRS for this schedule
        $frsDetail = FrsDetail::where('jadwal_id', $jadwal->id)
            ->whereHas('frs', function ($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->where('status', 'disetujui')
            ->with('frs') // Eager load FRS
            ->first();

        if (!$frsDetail || !$frsDetail->frs || !$frsDetail->jadwal->tahun_ajar_id) {
            return redirect()->route('dosen.jadwal.mahasiswa', $jadwal->id)
                ->with('error', 'Data FRS (termasuk Tahun Ajar) tidak lengkap atau tidak ditemukan untuk mahasiswa pada jadwal ini.');
        }
        $tahunAjarId = $frsDetail->jadwal->tahun_ajar_id;

        // Find existing nilai or create a new one for the form
        $nilai = Nilai::firstOrNew([
            'mahasiswa_id' => $mahasiswa->id,
            'mk_id' => $jadwal->mk_id,
            'dosen_id' => $jadwal->dosen_id, // Dosen who teaches this schedule
            'tahun_ajar_id' => $tahunAjarId,
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

        // Get tahun_ajar_id (same logic as in showForm)
        $frsDetail = FrsDetail::where('jadwal_id', $jadwal->id)
            ->whereHas('frs', function ($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->where('status', 'disetujui')
            ->with('frs')
            ->first();

        if (!$frsDetail || !$frsDetail->frs || !$frsDetail->jadwal->tahun_ajar_id) {
            return redirect()->route('dosen.jadwal.mahasiswa', $jadwal->id)
                ->with('error', 'Gagal menyimpan nilai. Data FRS (Tahun Ajar) tidak lengkap.');
        }
        $tahunAjarId = $frsDetail->jadwal->tahun_ajar_id;

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
                'mahasiswa_id' => $mahasiswa->id,
                'mk_id' => $jadwal->mk_id,
                'dosen_id' => $jadwal->dosen_id,
                'tahun_ajar_id' => $tahunAjarId,
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
