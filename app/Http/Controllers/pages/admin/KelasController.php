<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.kelas.index');
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
            'dosen_id' => 'required|exists:dosen,id',
            'pararel' => 'required|string',
        ]);

        $kelas = Kelas::create($validated);

        return response()->json([
            'message' => 'Kelas berhasil',
            'data' => $kelas
        ]);
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
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'pararel' => 'required|string',
        ]);

        $kelas->update($validated);

        return response()->json([
            'message' => 'kelas berhasil diupdate',
            'data' => $kelas
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json([
            'message' => 'kelas berhasil dihapus'
        ]);
    }
}
