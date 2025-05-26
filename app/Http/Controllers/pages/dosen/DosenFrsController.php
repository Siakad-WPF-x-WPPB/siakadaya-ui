<?php

namespace App\Http\Controllers\pages\dosen;

use App\Http\Controllers\Controller;
use App\Http\Resources\dosen\FrsCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\DetailJadwal;
use App\Models\Frs;
use App\Models\FrsDetail;
use App\Models\Jadwal;
use App\Models\PersetujuanFrs;

class DosenFrsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $dosen = Auth::guard('dosen')->user();

            if (!$dosen || !$dosen->is_wali) {
                abort(403, 'Akses ditolak. Hanya dosen wali yang dapat mengakses halaman ini.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        return view('pages.dosen.frs.index');
    }

    public function getFrsData(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();

        // Get FRS only from mahasiswa in classes where this dosen is wali
        $query = Frs::with(['mahasiswa.kelas', 'frsDetail.jadwal.matakuliah'])
            ->whereHas('mahasiswa.kelas', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            });

        // Apply search if provided
        $searchValue = $request->get('search')['value'] ?? '';
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->whereHas('mahasiswa', function ($mq) use ($searchValue) {
                    $mq->where('nama', 'like', "%{$searchValue}%")
                      ->orWhere('nrp', 'like', "%{$searchValue}%");
                });
            });
        }

        // Get paginated results
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);

        $frsData = $query->orderBy('tanggal_pengisian', 'desc')
                        ->offset($start)
                        ->limit($length)
                        ->get();

        return new FrsCollection($frsData);
    }

    public function show($id)
    {
        $dosen = Auth::guard('dosen')->user();

        // Check if this dosen wali can access this FRS
        $frs = Frs::with(['mahasiswa.kelas', 'frsDetail.jadwal.matakuliah', 'frsDetail.jadwal.dosen'])
            ->whereHas('mahasiswa.kelas', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            })
            ->findOrFail($id);

        return view('pages.dosen.frs.show', compact('frs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:disetujui,ditolak'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $persetujuan = FrsDetail::with('frs')->findOrFail($id);
            $oldStatus = $persetujuan->status;
            $newStatus = $request->status;

            // Update FrsDetail
            $persetujuan->status = $newStatus;
            $persetujuan->tanggal_persetujuan = now();
            $persetujuan->save();

            if ($newStatus === 'disetujui') {
                $mahasiswaId = $persetujuan->frs->mahasiswa_id;
                $jadwalId = $persetujuan->jadwal_id;

                DetailJadwal::firstOrCreate(
                    [
                        'jadwal_id' => $jadwalId,
                        'mahasiswa_id' => $mahasiswaId,
                    ]
                );
            } elseif ($newStatus === 'ditolak' && $oldStatus === 'disetujui') {
                if ($persetujuan->frs) {
                    $mahasiswaId = $persetujuan->frs->mahasiswa_id;
                    $jadwalId = $persetujuan->jadwal_id;
                    if ($mahasiswaId && $jadwalId) {
                        DetailJadwal::where('jadwal_id', $jadwalId)
                            ->where('mahasiswa_id', $mahasiswaId)
                            ->delete();
                    }
                }
            }

            DB::commit();

            // Redirect back to the show FRS page
            return redirect()->route('dosen.frs.show', $persetujuan->frs_id)
                ->with('success', 'Status persetujuan berhasil diperbarui dan detail jadwal disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating FRS status for FrsDetail ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server: ' . $e->getMessage());
        }
    }
}
