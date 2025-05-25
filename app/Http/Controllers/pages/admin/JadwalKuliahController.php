<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Ruangan;
use App\Models\ProgramStudi;
use App\Models\TahunAjar;

class JadwalKuliahController extends Controller
{
    private function validateJadwalData(Request $request, $id = null)
    {
      $rules = [
          'kelas_id' => 'required|exists:kelas,id',
          'dosen_id' => 'required|exists:dosen,id',
          'mk_id' => 'required|exists:matakuliah,id',
          'ruangan_id' => 'required|exists:ruangan,id',
          'tahun_ajar_id' => 'required|exists:tahun_ajar,id',
          'hari' => [
              'required',
              'string',
              'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'
          ],
          'jam_mulai' => [
              'required',
              'date_format:H:i',
              function ($attribute, $value, $fail) use ($request) {
                  // validate time range
                  $time = strtotime($value);
                  $minTime = strtotime('07:00');
                  $maxTime = strtotime('20:00');

                  if ($time < $minTime || $time > $maxTime) {
                      $fail('Jam mulai harus antara 07:00 dan 20:00.');
                  }
              }
          ],
          'jam_selesai' => [
              'required',
              'date_format:H:i',
              'after:jam_mulai',
              function ($attribute, $value, $fail) use ($request) {
                  // Validate time range
                  $time = strtotime($value);
                  $minTime = strtotime('07:00');
                  $maxTime = strtotime('21:00');

                  if ($time < $minTime || $time > $maxTime) {
                      $fail('Jam selesai harus antara 07:00 - 21:00');
                  }

                  // Validate minimum duration (e.g., 50 minutes)
                  if ($request->jam_mulai) {
                      $startTime = strtotime($request->jam_mulai);
                      $endTime = strtotime($value);
                      $duration = ($endTime - $startTime) / 60; // in minutes

                      if ($duration < 50) {
                          $fail('Durasi kuliah minimal 50 menit');
                      }

                      if ($duration > 300) {
                          $fail('Durasi kuliah maksimal 5 jam');
                      }
                  }
              }
          ],
      ];

      $messages = [
        'kelas_id.required' => 'Kelas harus dipilih',
        'kelas_id.exists' => 'Kelas yang dipilih tidak valid',
        'dosen_id.required' => 'Dosen harus dipilih',
        'dosen_id.exists' => 'Dosen yang dipilih tidak valid',
        'mk_id.required' => 'Mata kuliah harus dipilih',
        'mk_id.exists' => 'Mata kuliah yang dipilih tidak valid',
        'ruangan_id.required' => 'Ruangan harus dipilih',
        'ruangan_id.exists' => 'Ruangan yang dipilih tidak valid',
        'tahun_ajar_id.required' => 'Tahun ajar harus dipilih',
        'tahun_ajar_id.exists' => 'Tahun ajar yang dipilih tidak valid',
        'jam_mulai.required' => 'Jam mulai harus diisi',
        'jam_mulai.date_format' => 'Format jam mulai tidak valid (HH:MM)',
        'jam_selesai.required' => 'Jam selesai harus diisi',
        'jam_selesai.date_format' => 'Format jam selesai tidak valid (HH:MM)',
        'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai',
        'hari.required' => 'Hari harus dipilih',
        'hari.in' => 'Hari yang dipilih tidak valid',
      ];

      $validated = $request->validate($rules, $messages);

      // Additional business logic validation
      $this->validateScheduleConflicts($validated, $id);

      return $validated;
    }

    // Bussiness logic to check for schedule conflicts
    private function validateScheduleConflicts($data, $excludeId = null)
    {
        $conflicts = [];

        // Check dosen conflict (within same tahun_ajar)
        $dosenConflict = Jadwal::where('dosen_id', $data['dosen_id'])
            ->where('hari', $data['hari'])
            ->where('tahun_ajar_id', $data['tahun_ajar_id']) // Add tahun_ajar filter
            ->when($excludeId, function($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->where(function($query) use ($data) {
                $query->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                      ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                      ->orWhere(function($q) use ($data) {
                          $q->where('jam_mulai', '<=', $data['jam_mulai'])
                            ->where('jam_selesai', '>=', $data['jam_selesai']);
                      });
            })
            ->with(['kelas.programStudi', 'matakuliah', 'tahunAjar'])
            ->first();

        if ($dosenConflict) {
            $conflicts['dosen_id'] = "Dosen sudah memiliki jadwal pada {$data['hari']} jam {$dosenConflict->jam_mulai}-{$dosenConflict->jam_selesai} untuk mata kuliah {$dosenConflict->matakuliah->nama} di tahun ajar {$dosenConflict->tahunAjar->semester} {$dosenConflict->tahunAjar->tahun_mulai}/{$dosenConflict->tahunAjar->tahun_akhir}";
        }

        // Check ruangan conflict (within same tahun_ajar)
        $ruanganConflict = Jadwal::where('ruangan_id', $data['ruangan_id'])
            ->where('hari', $data['hari'])
            ->where('tahun_ajar_id', $data['tahun_ajar_id']) // Add tahun_ajar filter
            ->when($excludeId, function($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->where(function($query) use ($data) {
                $query->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                      ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                      ->orWhere(function($q) use ($data) {
                          $q->where('jam_mulai', '<=', $data['jam_mulai'])
                            ->where('jam_selesai', '>=', $data['jam_selesai']);
                      });
            })
            ->with(['dosen', 'matakuliah', 'tahunAjar'])
            ->first();

        if ($ruanganConflict) {
            $conflicts['ruangan_id'] = "Ruangan sudah digunakan pada {$data['hari']} jam {$ruanganConflict->jam_mulai}-{$ruanganConflict->jam_selesai} untuk mata kuliah {$ruanganConflict->matakuliah->nama} di tahun ajar {$ruanganConflict->tahunAjar->semester} {$ruanganConflict->tahunAjar->tahun_mulai}/{$ruanganConflict->tahunAjar->tahun_akhir}";
        }

        // Check kelas conflict (within same tahun_ajar)
        $kelasConflict = Jadwal::where('kelas_id', $data['kelas_id'])
            ->where('hari', $data['hari'])
            ->where('tahun_ajar_id', $data['tahun_ajar_id']) // Add tahun_ajar filter
            ->when($excludeId, function($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->where(function($query) use ($data) {
                $query->whereBetween('jam_mulai', [$data['jam_mulai'], $data['jam_selesai']])
                      ->orWhereBetween('jam_selesai', [$data['jam_mulai'], $data['jam_selesai']])
                      ->orWhere(function($q) use ($data) {
                          $q->where('jam_mulai', '<=', $data['jam_mulai'])
                            ->where('jam_selesai', '>=', $data['jam_selesai']);
                      });
            })
            ->with(['dosen', 'matakuliah', 'tahunAjar'])
            ->first();

        if ($kelasConflict) {
            $conflicts['kelas_id'] = "Kelas sudah memiliki jadwal pada {$data['hari']} jam {$kelasConflict->jam_mulai}-{$kelasConflict->jam_selesai} untuk mata kuliah {$kelasConflict->matakuliah->nama} di tahun ajar {$kelasConflict->tahunAjar->semester} {$kelasConflict->tahunAjar->tahun_mulai}/{$kelasConflict->tahunAjar->tahun_akhir}";
        }

        if (!empty($conflicts)) {
            throw ValidationException::withMessages($conflicts);
        }
    }

    public function getKelasByProdi($prodiId)
    {
      try {
          $kelas = Kelas::where('prodi_id', $prodiId)
                      ->with('programStudi')
                      ->get()
                      ->map(function($k) {
                          return [
                              'id' => $k->id,
                              'pararel' => $k->pararel,
                              'display_name' => $k->programStudi->kode . '-' . $k->pararel
                          ];
                      });

          return response()->json([
              'success' => true,
              'data' => $kelas
          ]);
      } catch (\Exception $e) {
          return response()->json([
              'success' => false,
              'message' => 'Error fetching kelas: ' . $e->getMessage()
          ], 500);
      }
    }

    public function getMatakuliahByProdi($prodiId)
    {
    try {
        // Validate prodi exists
        $prodi = ProgramStudi::findOrFail($prodiId);

        $matakuliah = Matakuliah::where('prodi_id', $prodiId)
                                ->orderBy('nama')
                                ->get()
                                ->map(function($mk) {
                                    return [
                                        'id' => $mk->id,
                                        'nama' => $mk->nama,
                                        'kode' => $mk->kode,
                                        'sks' => $mk->sks,
                                        'display_name' => $mk->nama . ' (' . $mk->kode . ') - ' . $mk->sks . ' SKS'
                                    ];
                                });

        return response()->json([
            'success' => true,
            'data' => $matakuliah,
            'message' => "Found {$matakuliah->count()} mata kuliah for {$prodi->nama}"
        ]);
      } catch (ModelNotFoundException $e) {
          return response()->json([
              'success' => false,
              'message' => 'Program Studi tidak ditemukan'
          ], 404);
      } catch (Exception $e) {
          Log::error('Error fetching matakuliah by prodi: ' . $e->getMessage());
          return response()->json([
              'success' => false,
              'message' => 'Terjadi kesalahan saat mengambil data mata kuliah'
          ], 500);
      }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.jadwalKuliah.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      try {
          $kelas = Kelas::with('programStudi')->orderBy('pararel')->get();
          $dosen = Dosen::orderBy('nama')->get();
          $matakuliah = Matakuliah::orderBy('nama')->get();
          $ruangan = Ruangan::orderBy('nama')->get();
          $prodi = ProgramStudi::orderBy('nama')->get();
          $tahunAjar = TahunAjar::where('status', 'Aktif')
                          ->orderBy('tahun_mulai', 'desc')
                          ->orderBy('semester')
                          ->get();

          return view('pages.admin.jadwalKuliah.form', compact('kelas', 'dosen', 'matakuliah', 'ruangan', 'prodi','tahunAjar'));
      } catch (Exception $e) {
          Log::error('Error loading create form: ' . $e->getMessage());
          return redirect()->route('admin-jadwal-kuliah-index')->with('error', 'Terjadi kesalahan saat memuat form');
      }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try {
          // Start a database transaction
          DB::beginTransaction();

          // validate the request data
          $validated = $this->validateJadwalData($request);

          // create a new Jadwal record
          $jadwal = Jadwal::create($validated);

          // Commit the transaction
          DB::commit();

          // Handle different response types based on request
          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => true,
                  'message' => 'Jadwal berhasil ditambahkan',
                  'data' => $jadwal->load(['kelas.programStudi', 'dosen', 'matakuliah', 'ruangan', 'tahunAjar'])
              ], 201);
          }

          return redirect()->route('admin-jadwal-kuliah-index')
                          ->with('success', 'Jadwal berhasil ditambahkan');

      } catch (ValidationException $e) {
          // Rollback the transaction if validation fails
          DB::rollBack();

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => false,
                  'message' => 'Validasi gagal',
                  'errors' => $e->errors()
              ], 422);
          }

          return redirect()->back()
                          ->withErrors($e->errors())
                          ->withInput();
      } catch (Exception $e) {
          // Rollback the transaction if any other error occurs
          DB::rollBack();
          Log::error('Error creating jadwal: ' . $e->getMessage());

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => false,
                  'message' => 'Terjadi kesalahan saat menyimpan data'
              ], 500);
          }

          return redirect()->back()
                          ->withInput()
                          ->with('error', 'Terjadi kesalahan saat menyimpan data');
      }
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
      try {
          $jadwal = Jadwal::with(['kelas.programStudi'])->findOrFail($id);
          $kelas = Kelas::with('programStudi')->orderBy('pararel')->get();
          $dosen = Dosen::orderBy('nama')->get();
          $matakuliah = Matakuliah::orderBy('nama')->get();
          $ruangan = Ruangan::orderBy('nama')->get();
          $prodi = ProgramStudi::orderBy('nama')->get();
          $tahunAjar = TahunAjar::orderByRaw("CASE WHEN status = 'Aktif' THEN 0 ELSE 1 END")
                          ->orderBy('tahun_mulai', 'desc')
                          ->orderBy('semester')
                          ->get();

          return view('pages.admin.jadwalKuliah.form', compact('jadwal', 'kelas', 'dosen', 'matakuliah', 'ruangan', 'prodi', 'tahunAjar'));
      } catch (ModelNotFoundException $e) {
          return redirect()->route('admin-jadwal-kuliah-index')
                          ->with('error', 'Jadwal tidak ditemukan');
      } catch (Exception $e) {
          Log::error('Error loading edit form: ' . $e->getMessage());
          return redirect()->route('admin-jadwal-kuliah-index')
                          ->with('error', 'Terjadi kesalahan saat memuat form');
      }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      // dd($request->all());

      try {
          DB::beginTransaction();

          $jadwal = Jadwal::findOrFail($id);
          $validated = $this->validateJadwalData($request, $jadwal->id);

          $jadwal->update($validated);

          DB::commit();

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => true,
                  'message' => 'Jadwal berhasil diperbarui',
                  'data' => $jadwal->load(['kelas.programStudi', 'dosen', 'matakuliah', 'ruangan', 'tahunAjar'])
              ]);
          }

          return redirect()->route('admin-jadwal-kuliah-index')
                          ->with('success', 'Jadwal berhasil diperbarui');

      } catch (ValidationException $e) {
          DB::rollBack();

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => false,
                  'message' => 'Validasi gagal',
                  'errors' => $e->errors()
              ], 422);
          }

          return redirect()->back()
                          ->withErrors($e->errors())
                          ->withInput();

      } catch (ModelNotFoundException $e) {
          DB::rollBack();

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => false,
                  'message' => 'Jadwal tidak ditemukan'
              ], 404);
          }

          return redirect()->route('admin-jadwal-kuliah-index')
                          ->with('error', 'Jadwal tidak ditemukan');

      } catch (Exception $e) {
          DB::rollBack();
          Log::error('Error updating jadwal: ' . $e->getMessage());

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => false,
                  'message' => 'Terjadi kesalahan saat memperbarui data'
              ], 500);
          }

          return redirect()->back()
                          ->withInput()
                          ->with('error', 'Terjadi kesalahan saat memperbarui data');
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
      try {
          DB::beginTransaction();

          $jadwal = Jadwal::findOrFail($id);
          $jadwal->delete();

          DB::commit();

          return response()->json([
              'success' => true,
              'message' => 'Jadwal berhasil dihapus'
          ]);

      } catch (ModelNotFoundException $e) {
          DB::rollBack();
          return response()->json([
              'success' => false,
              'message' => 'Jadwal tidak ditemukan'
          ], 404);

      } catch (Exception $e) {
          DB::rollBack();
          Log::error('Error deleting jadwal: ' . $e->getMessage());

          return response()->json([
              'success' => false,
              'message' => 'Terjadi kesalahan saat menghapus data'
          ], 500);
      }
    }
}
