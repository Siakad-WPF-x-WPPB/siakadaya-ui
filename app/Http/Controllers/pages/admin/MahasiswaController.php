<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.mahasiswa.index');
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
        // validasi data mahasiswa
        $validated = $request->validate([
            'prodi_id' => 'required|exists:program_studi,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nrp' => 'required|unique:mahasiswa,nrp',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'telepon' => 'required|numeric',
            'email' => 'required|email|unique:mahasiswa,email',
            'password' => 'required|string|min:8',
            'agama' => 'required|string|max:255',
            'semester' => 'required|string|min:1',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'status' => ['required', Rule::in(['Aktif', 'Cuti', 'Keluar'])],
            'alamat_jalan' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string',
            'negara' => 'required|string',
            'kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kota' => 'required|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $mahasiswa = Mahasiswa::create($validated);

        return response()->json([
            'message' => 'Data mahasiswa berhasil disimpan.',
            'data' => $mahasiswa
        ], 201);
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
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validate([
            'prodi_id' => 'required|exists:program_studi,id',
            'kelas_id' => 'required|exists:kelas,id',
            'nrp' => 'required|unique:mahasiswa,nrp,',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'telepon' => 'required|numeric',
            'email' => 'required|email|unique:mahasiswa,email,',
            'password' => 'required|string|min:8',
            'agama' => 'required|string|max:255',
            'semester' => 'required|string|min:1',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'status' => ['required', Rule::in(['Aktif', 'Cuti', 'Keluar'])],
            'alamat_jalan' => 'required|string',
            'provinsi' => 'required|string',
            'kode_pos' => 'required|string',
            'negara' => 'required|string',
            'kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kota' => 'required|string',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $mahasiswa->update($validated);

        return response()->json([
            'message' => 'Data mahasiswa berhasil diperbarui.',
            'data' => $mahasiswa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json([
            'message' => 'Data mahasiswa berhasil dihapus.'
        ]);
    }
}
