<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.dosen.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.dosen.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|unique:dosen,nip',
            'prodi_id' => 'required|exists:program_studi,id',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon' => 'required|string',
            'email' => 'required|email|unique:dosen,email',
            'password' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string',
            'golongan_akhir' => 'required|string',
            'is_wali' => 'required|boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $dosen = Dosen::create($validated);

        return response()->json([
            'message' => 'Dosen berhasil',
            'data' => $dosen
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
        $dosen = Dosen::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|unique:dosen,nip,' . $dosen->id,
            'prodi_id' => 'required|exists:program_studi,id',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon' => 'required|string',
            'email' => 'required|email|unique:dosen,email,' . $dosen->id,
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string',
            'golongan_akhir' => 'required|string',
            'is_wali' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            $validated['password'] = $dosen->password;
        }

        $dosen->update($validated);

        return response()->json([
            'message' => 'Dosen berhasil diupdate',
            'data' => $dosen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return response()->json([
            'message' => 'Dosen berhasil dihapus'
        ]);
    }
}
