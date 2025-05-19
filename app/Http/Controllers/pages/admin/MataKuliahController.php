<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.mataKuliah.index');
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
        $validated = $request->validate([
            'prodi_id' => 'required|exists:program_studi,id',
            'kode' => 'required|unique:matakuliah,kode',
            'nama' => 'required|string|max:255',
            'semester' => 'required|numeric|min:1',
            'sks' => 'required|numeric|min:1',
            'tipe_matakuliah' => ['required', Rule::in(['MPK', 'MPI', 'MW'])],
        ]);

        $matakuliah = Matakuliah::create($validated);

        return response()->json([
            'message' => 'Data matakuliah berhasil disimpan.',
            'data' => $matakuliah
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
        $matakuliah = Matakuliah::findOrFail($id);

        $validated = $request->validate([
            'prodi_id' => 'required|exists:program_studi,id',
            'kode' => ['required', Rule::unique('matakuliah', 'kode')->ignore($matakuliah->id)],
            'nama' => 'required|string|max:255',
            'semester' => 'required|numeric|min:1',
            'sks' => 'required|numeric|min:1',
            'tipe_matakuliah' => ['required', Rule::in(['MPK', 'MPI', 'MW'])],
        ]);

        $matakuliah->update($validated);

        return response()->json([
            'message' => 'Data matakuliah berhasil diperbarui.',
            'data' => $matakuliah
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        $matakuliah->delete();

        return response()->json([
            'message' => 'Data matakuliah berhasil dihapus.'
        ]);
    }
}
