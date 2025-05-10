<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjar;
use Illuminate\Http\Request;

class TahunAjarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.tahunAjar.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi data
        $validated = $request->validate([
            'semester' => 'required|string|min:1',
            'tahun' => 'required|numeric',
        ]);

        $tahunAjar = TahunAjar::create($validated);

        return response()->json([
            'message' => 'Data tahun ajar berhasil ditambahkan',
            'data' => $tahunAjar
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
        $tahunAjar = TahunAjar::findOrFail($id);

        $validated = $request->validate([
            'semester' => 'required|string|min:1',
            'tahun' => 'required|numeric',
        ]);

        $tahunAjar->update($validated);

        return response()->json([
            'message' => 'Data tahun ajar berhasil diperbarui.',
            'data' => $tahunAjar
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tahunAjar = TahunAjar::findOrFail($id);
        $tahunAjar->delete();

        return response()->json([
            'message' => 'Data tahun ajar berhasil dihapus.'
        ]);
    }
}
