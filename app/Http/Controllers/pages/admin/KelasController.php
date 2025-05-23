<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Kelas;
use App\Models\ProgramStudi;
use App\Models\Dosen;

class KelasController extends Controller
{
    private function validateKelasData(Request $request, $id = null)
    {
      // create unique rule pararel with the same program studi
      $pararelRule = $id ? "required|string|max:10" : 'required|string|max:10';

      return $request->validate([
        'prodi_id' => 'required|exists:program_studi,id',
        'dosen_id' => 'required|exists:dosen,id',
        'pararel' => [
            'required',
            'string',
            'max:10',
            // Ensure pararel is unique within the same program studi
            Rule::unique('kelas')->where(function ($query) use ($request) {
                return $query->where('prodi_id', $request->prodi_id);
            })->ignore($id)
        ],
      ], [
          'prodi_id.required' => 'Program studi harus dipilih.',
          'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
          'dosen_id.required' => 'Dosen wali harus dipilih.',
          'dosen_id.exists' => 'Dosen yang dipilih tidak valid.',
          'pararel.required' => 'Nama kelas/pararel wajib diisi.',
          'pararel.string' => 'Nama kelas/pararel harus berupa teks.',
          'pararel.max' => 'Nama kelas/pararel maksimal 10 karakter.',
          'pararel.unique' => 'Nama kelas/pararel sudah ada untuk program studi ini.',
      ]);
    }

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
      $dosen = Dosen::all();
      $prodi = ProgramStudi::all();

      return view('pages.admin.kelas.form', compact('dosen', 'prodi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try {
        // validate the request data
        $validated = $this->validateKelasData($request);

        // create a new kelas record
        $kelas = Kelas::create($validated);

        // handle different response based on request type
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil disimpan.',
                'data' => $kelas
            ], 201);
        }

        return redirect()->route('admin-kelas-index')->with('success', 'Data kelas berhasil disimpan.');
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
        Log::error('Error creating kelas: ' . $e->getMessage());

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
        $kelas = Kelas::findOrFail($id);
        $dosen = Dosen::all();
        $prodi = ProgramStudi::all();

        return view('pages.admin.kelas.form', compact('kelas', 'dosen', 'prodi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // validate the request data
            $validated = $this->validateKelasData($request, $id);

            // find the existing kelas record
            $kelas = Kelas::findOrFail($id);

            // update the kelas record
            $kelas->update($validated);

            // handle different response based on request type
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data kelas berhasil diperbarui.',
                    'data' => $kelas
                ], 200);
            }

            return redirect()->route('admin-kelas-index')->with('success', 'Data kelas berhasil diperbarui.');
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
                    'message' => 'Kelas tidak ditemukan.'
                ], 404);
            }

            return redirect()->back()
                ->with('error', 'Kelas tidak ditemukan.');
        } catch (Exception $e) {
            Log::error('Error updating kelas: ' . $e->getMessage());

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
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return response()->json([
            'message' => 'kelas berhasil dihapus'
        ]);
    }
}
