<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Ruangan;

class JadwalKuliahController extends Controller
{
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
        $kelas = Kelas::all();
        $dosen = Dosen::all();
        $matakuliah = Matakuliah::all();
        $ruangan = Ruangan::all();

        return view('pages.admin.jadwalKuliah.form', compact('kelas', 'dosen', 'matakuliah', 'ruangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      // dd($request->all());
      $validator = Validator::make($request->all(), [
            'kelas_id' => 'required|exists:kelas,id',
            'dosen_id' => 'required|exists:dosen,id',
            'mk_id' => 'required|exists:matakuliah,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => 'required|string'
        ], [
          // Custom error messages (optional)
          'kelas_id.required' => 'Kelas harus dipilih',
          'dosen_id.required' => 'Dosen harus dipilih',
          'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai',
        ]);

        if ($validator->fails()) {
              if ($validator->fails()) {
              return response()->json([
                  'success' => false,
                  'message' => 'Validasi gagal',
                  'errors' => $validator->errors()
              ], 422);
          }
        }

        try {
            $jadwal = Jadwal::create($validator->validated());

            return redirect()->route('admin-jadwal-kuliah-index')->with('success', 'Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
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
        $jadwal = Jadwal::findOrFail($id);
        $kelas = Kelas::all();
        $dosen = Dosen::all();
        $matakuliah = Matakuliah::all();
        $ruangan = Ruangan::all();

        return view('pages.admin.jadwalKuliah.form', compact('jadwal', 'kelas', 'dosen', 'matakuliah', 'ruangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $data = $request->all();
          // Format times if needed
          if (isset($data['jam_mulai'])) {
              $data['jam_mulai'] = date('H:i', strtotime($data['jam_mulai']));
          }

          if (isset($data['jam_selesai'])) {
              $data['jam_selesai'] = date('H:i', strtotime($data['jam_selesai']));
          }

          // dd($data);

        $validator = Validator::make($data, [
            'kelas_id' => 'required|exists:kelas,id',
            'dosen_id' => 'required|exists:dosen,id',
            'mk_id' => 'required|exists:matakuliah,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'hari' => 'required|string'
        ], [
          // Custom error messages (optional)
          'kelas_id.required' => 'Kelas harus dipilih',
          'dosen_id.required' => 'Dosen harus dipilih',
          'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai',
        ]);

        if ($validator->fails()) {
              return response()->json([
                  'success' => false,
                  'message' => 'Validasi gagal',
                  'errors' => $validator->errors()
              ], 422);
          }

        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->update($validator->validated());

            return redirect()->route('admin-jadwal-kuliah-index')->with('success', 'Jadwal berhasil diubah');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'message' => 'jadwal berhasil dihapus'
        ]);
    }
}
