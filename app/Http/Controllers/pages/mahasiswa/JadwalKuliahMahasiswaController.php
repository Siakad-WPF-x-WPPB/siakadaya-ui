<?php

namespace App\Http\Controllers\pages\mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Resources\mahasiswa\JadwalMahasiswaCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\Jadwal;
use App\Models\TahunAjar;

class JadwalKuliahMahasiswaController extends Controller
{
    protected $urutanHari = [
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
    ];

    public function getAll(Request $request)
    {
        $query = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan', 'tahunAjar'])
            ->whereHas('kelas.mahasiswa', function ($q) {
                $q->where('id', auth('mahasiswa_api')->id());
            });

        // Apply filters if provided
        $this->applyFilters($query, $request);

        $jadwal = $query->orderBy('hari')
                        ->orderBy('jam_mulai')
                        ->get();

        return new JadwalMahasiswaCollection($jadwal);
    }

    public function getToday(Request $request)
    {
        $hariIni = ucfirst(Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd'));

        $query = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan', 'tahunAjar'])
            ->where('hari', $hariIni)
            ->whereHas('kelas.mahasiswa', function ($q) {
                $q->where('id', auth('mahasiswa_api')->id());
            });

        $this->applyFilters($query, $request);

        $jadwal = $query->orderBy('jam_mulai')->get();

        return new JadwalMahasiswaCollection($jadwal);
    }

    public function getTomorrow(Request $request)
    {
        $hariBerikutnya = ucfirst(Carbon::now('Asia/Jakarta')->addDay()->locale('id')->isoFormat('dddd'));

        $query = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan', 'tahunAjar'])
            ->where('hari', $hariBerikutnya)
            ->whereHas('kelas.mahasiswa', function ($q) {
                $q->where('id', auth('mahasiswa_api')->id());
            });

        $this->applyFilters($query, $request);

        $jadwal = $query->orderBy('jam_mulai')->get();

        return new JadwalMahasiswaCollection($jadwal);
    }

    public function getPerProdi(Request $request)
    {
        $mahasiswa = auth('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa not authenticated'
            ], 401);
        }

        // Get jadwal from the same program studi
        $query = Jadwal::with(['dosen', 'matakuliah', 'kelas', 'ruangan', 'tahunAjar'])
            ->whereHas('kelas', function ($q) use ($mahasiswa) {
                $q->where('prodi_id', $mahasiswa->prodi_id);
            });

        $this->applyFilters($query, $request);

        $jadwal = $query->orderBy('hari')
                        ->orderBy('jam_mulai')
                        ->get();

        return new JadwalMahasiswaCollection($jadwal);
    }

    public function getDropdownOptions(Request $request)
    {
        $mahasiswa = auth('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa not authenticated'
            ], 401);
        }

        // Get available tahun ajar that have jadwal for this mahasiswa's kelas
        $tahunAjarList = TahunAjar::whereHas('jadwal.kelas.mahasiswa', function ($query) use ($mahasiswa) {
            $query->where('id', $mahasiswa->id);
        })
        ->orderBy('tahun_mulai', 'desc')
        ->orderBy('semester', 'desc')
        ->get()
        ->map(function ($ta) {
            return [
                'id' => $ta->id,
                'semester' => $ta->semester,
                'tahun_mulai' => $ta->tahun_mulai,
                'tahun_akhir' => $ta->tahun_akhir,
                'display_name' => $ta->semester . ' ' . $ta->tahun_mulai . '/' . $ta->tahun_akhir,
                'status' => $ta->status,
                'is_active' => $ta->status === 'Aktif'
            ];
        });

        $semesters = $tahunAjarList->pluck('semester')->unique()->values()->toArray();

        // Get unique tahun ajar (without semester)
        $tahunAjarUnique = $tahunAjarList->groupBy(function ($item) {
            return $item['tahun_mulai'] . '/' . $item['tahun_akhir'];
        })->map(function ($group, $key) {
            $first = $group->first();
            return [
                'tahun_mulai' => $first['tahun_mulai'],
                'tahun_akhir' => $first['tahun_akhir'],
                'display_name' => $key,
                'semesters' => $group->pluck('semester')->unique()->values()->toArray()
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'tahun_ajar_list' => $tahunAjarList,
                'semesters' => $semesters,
                'tahun_ajar_unique' => $tahunAjarUnique,
                'active_tahun_ajar' => $tahunAjarList->where('is_active', true)->first()
            ]
        ]);
    }

    public function getDropdownOptionsProdi(Request $request)
    {
        $mahasiswa = auth('mahasiswa_api')->user();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Mahasiswa not authenticated'
            ], 401);
        }

        // Get available tahun ajar that have jadwal for this mahasiswa's program studi
        $tahunAjarList = TahunAjar::whereHas('jadwal.kelas', function ($query) use ($mahasiswa) {
            $query->where('prodi_id', $mahasiswa->prodi_id);
        })
        ->orderBy('tahun_mulai', 'desc')
        ->orderBy('semester', 'desc')
        ->get()
        ->map(function ($ta) {
            return [
                'id' => $ta->id,
                'semester' => $ta->semester,
                'tahun_mulai' => $ta->tahun_mulai,
                'tahun_akhir' => $ta->tahun_akhir,
                'display_name' => $ta->semester . ' ' . $ta->tahun_mulai . '/' . $ta->tahun_akhir,
                'status' => $ta->status,
                'is_active' => $ta->status === 'Aktif'
            ];
        });

        $semesters = $tahunAjarList->pluck('semester')->unique()->values()->toArray();

        // Get unique tahun ajar (without semester)
        $tahunAjarUnique = $tahunAjarList->groupBy(function ($item) {
            return $item['tahun_mulai'] . '/' . $item['tahun_akhir'];
        })->map(function ($group, $key) {
            $first = $group->first();
            return [
                'tahun_mulai' => $first['tahun_mulai'],
                'tahun_akhir' => $first['tahun_akhir'],
                'display_name' => $key,
                'semesters' => $group->pluck('semester')->unique()->values()->toArray()
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'tahun_ajar_list' => $tahunAjarList,
                'semesters' => $semesters,
                'tahun_ajar_unique' => $tahunAjarUnique,
                'active_tahun_ajar' => $tahunAjarList->where('is_active', true)->first(),
                'program_studi' => [
                    'id' => $mahasiswa->programStudi->id,
                    'nama' => $mahasiswa->programStudi->nama,
                    'kode' => $mahasiswa->programStudi->kode
                ]
            ]
        ]);
    }

    private function applyFilters($query, Request $request)
    {
        // Filter by specific tahun ajar ID (highest priority)
        if ($request->has('tahun_ajar_id') && !empty($request->tahun_ajar_id)) {
            $query->where('tahun_ajar_id', $request->tahun_ajar_id);
            return;
        }

        // Filter by semester and tahun range
        if ($request->has('semester') && !empty($request->semester)) {
            $query->whereHas('tahunAjar', function ($q) use ($request) {
                $q->where('semester', $request->semester);

                // Also filter by tahun if provided
                if ($request->has('tahun_mulai') && $request->has('tahun_akhir')) {
                    $q->where('tahun_mulai', $request->tahun_mulai)
                      ->where('tahun_akhir', $request->tahun_akhir);
                }
            });
        } elseif ($request->has('tahun_mulai') && $request->has('tahun_akhir')) {
            // Filter by tahun range only
            $query->whereHas('tahunAjar', function ($q) use ($request) {
                $q->where('tahun_mulai', $request->tahun_mulai)
                  ->where('tahun_akhir', $request->tahun_akhir);
            });
        } else {
            // Default to active tahun ajar if no filters provided
            $query->whereHas('tahunAjar', function ($q) {
                $q->where('status', 'Aktif');
            });
        }
    }
}
