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
        return view('pages.admin.tahunAjar.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi data
        $validated = $request->validate([
            'semester' => 'required|string|min:1',
            'tahun' => 'required|string|min:1',
        ]);

        $tahunAjar = TahunAjar::create($validated);

        return redirect()->route('admin-tahun-ajar-index')->with([
            'message' => 'Data tahun ajar berhasil ditambahkan.',
            'data' => $tahunAjar
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
        $tahunAjar = TahunAjar::findOrFail($id);

        return view('pages.admin.tahunAjar.form', compact('tahunAjar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validasi data
        $validated = $request->validate([
            'semester' => 'required|string|min:1',
            'tahun' => 'required|string|min:1',
        ]);

        $tahunAjar = TahunAjar::findOrFail($id);
        $tahunAjar->update($validated);

        return redirect()->route('admin-tahun-ajar-index')->with([
            'message' => 'Data tahun ajar berhasil diubah.',
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
