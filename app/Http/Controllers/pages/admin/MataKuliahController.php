<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Matakuliah;
use App\Models\ProgramStudi;
class MataKuliahController extends Controller
{
    private function validateMatakuliahData(Request $request, $id = null)
    {
        $kodeRule = $id ? "required|string|max:10|unique:matakuliah,kode,{$id}" : 'required|string|max:10|unique:matakuliah,kode';
        return $request->validate([
            'prodi_id' => 'required|exists:program_studi,id',
            'kode' => $kodeRule,
            'nama' => 'required|string|max:100',
            'semester' => 'required|numeric|min:1',
            'sks' => 'required|numeric|min:1',
            'tipe' => ['required', Rule::in(['MPK', 'MPI', 'MW'])],
        ], [
            'prodi_id.required' => 'Program studi harus dipilih.',
            'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'kode.required' => 'Kode matakuliah wajib diisi.',
            'kode.max' => 'Kode matakuliah maksimal 10 karakter.',
            'kode.unique' => 'Kode matakuliah sudah digunakan oleh matakuliah lain.',
            'nama.required' => 'Nama matakuliah wajib diisi.',
            'nama.max' => 'Nama matakuliah maksimal 100 karakter.',
            'semester.required' => 'Semester wajib diisi.',
            'semester.numeric' => 'Semester harus berupa angka.',
            'semester.min' => 'Semester minimal 1.',
            'sks.required' => 'SKS wajib diisi.',
            'sks.numeric' => 'SKS harus berupa angka.',
            'sks.min' => 'SKS minimal 1.',
            'tipe.required' => 'Tipe matakuliah harus dipilih.',
            'tipe.in' => 'Tipe matakuliah harus MPK, MPI, atau MW.',
        ]);
    }

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
      $program_studi = ProgramStudi::all();

      return view('pages.admin.mataKuliah.form', compact('program_studi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try {
        // validate the request data
        $validated = $this->validateMatakuliahData($request);

        // create a new matakuliah record
        $matakuliah = Matakuliah::create($validated);

        // Handle different response types based on request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil disimpan.',
                'data' => $matakuliah
            ], 201);
        }

        return redirect()->route('admin-mata-kuliah-index')->with('success', 'Data mata kuliah berhasil disimpan.');
      } catch (ValidationException $e) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
      } catch (Exception $e) {
        Log::error('Error creating dosen: ' . $e->getMessage());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan data.');
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
        $matakuliah = Matakuliah::findOrFail($id);
        $program_studi = ProgramStudi::all();

        return view('pages.admin.mataKuliah.form', compact('matakuliah', 'program_studi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      try {
        $matakuliah = Matakuliah::findOrFail($id);

            // Jika sudah ada jadwal dan prodi_id ingin diubah, abaikan perubahan prodi_id
            if ($matakuliah->jadwal()->exists() && $request->prodi_id != $matakuliah->prodi_id) {
                // Validasi tanpa prodi_id
                $validated = $request->except('prodi_id');
                $validated = $this->validateMatakuliahData(new Request(array_merge($request->all(), [
                    'prodi_id' => $matakuliah->prodi_id
                ])), $id);

                $matakuliah->update($validated);
            }

            // validate the request data
            $validated = $this->validateMatakuliahData($request, $id);

        // update the matakuliah record
        $matakuliah->update($validated);

        // Handle different response types based on request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data mata kuliah berhasil diupdate.',
                'data' => $matakuliah
            ], 200);
        }

        return redirect()->route('admin-mata-kuliah-index')->with('success', 'Data mata kuliah berhasil diupdate.');
      } catch (ValidationException $e) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
      } catch (ModelNotFoundException $e) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Mata kuliah tidak ditemukan.'
            ], 404);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', 'Mata kuliah tidak ditemukan.');
      } catch (Exception $e) {
        Log::error('Error updating mata kuliah: ' . $e->getMessage());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat memperbarui data.');
      }
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
