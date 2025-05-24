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

use App\Models\ProgramStudi;

class ProgramStudiController extends Controller
{
    private function validateProgramStudiData(Request $request, $id = null)
    {
        $rules = [
            'kode' => [
                'required',
                'string',
                'max:25',
                'regex:/^[A-Z0-9]+$/', // Only uppercase letters and numbers
                Rule::unique('program_studi', 'kode')->ignore($id)
            ],
            'nama' => [
                'required',
                'string',
                'max:100',
                'min:3'
            ]
        ];

        $messages = [
            'kode.required' => 'Kode program studi harus diisi',
            'kode.max' => 'Kode program studi maksimal 25 karakter',
            'kode.regex' => 'Kode program studi hanya boleh berisi huruf kapital dan angka',
            'kode.unique' => 'Kode program studi sudah digunakan',
            'nama.required' => 'Nama program studi harus diisi',
            'nama.max' => 'Nama program studi maksimal 100 karakter',
            'nama.min' => 'Nama program studi minimal 3 karakter'
        ];

        $validated = $request->validate($rules, $messages);

        // Additional business logic validation
        $this->validateProgramStudiBusinessRules($validated, $id);

        return $validated;
    }

    private function validateProgramStudiBusinessRules($data, $excludeId = null)
    {
        $conflicts = [];

        // Check if nama already exists (case insensitive)
        $namaExists = ProgramStudi::whereRaw('LOWER(nama) = ?', [strtolower($data['nama'])])
            ->when($excludeId, function($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->exists();

        if ($namaExists) {
            $conflicts['nama'] = 'Nama program studi sudah ada (tidak boleh duplikat)';
        }

        // Check if kode follows standard format (e.g., minimum 2 characters)
        if (strlen($data['kode']) < 2) {
            $conflicts['kode'] = 'Kode program studi minimal 2 karakter';
        }

        if (!empty($conflicts)) {
            throw ValidationException::withMessages($conflicts);
        }
    }

    private function checkProgramStudiCanBeDeleted($programStudi)
    {
        $relatedData = [];

        // Check if has related Kelas
        $kelasCount = $programStudi->kelas()->count();
        if ($kelasCount > 0) {
            $relatedData[] = "{$kelasCount} kelas";
        }

        // Check if has related Matakuliah
        $matakuliahCount = $programStudi->matakuliah()->count();
        if ($matakuliahCount > 0) {
            $relatedData[] = "{$matakuliahCount} mata kuliah";
        }

        if (!empty($relatedData)) {
            throw new Exception(
                'Program Studi tidak dapat dihapus karena masih memiliki: ' .
                implode(', ', $relatedData) . '. Hapus data terkait terlebih dahulu.'
            );
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.programStudi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      try {
          return view('pages.admin.programStudi.form');
      } catch (Exception $e) {
          Log::error('Error loading create form: ' . $e->getMessage());
          return redirect()->route('admin-program-studi-index')->with('error', 'Terjadi kesalahan saat memuat form');
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

          // Validate the request data
          $validated = $this->validateProgramStudiData($request);

          // Create a new Program Studi record
          $programStudi = ProgramStudi::create($validated);

          // Commit the transaction
          DB::commit();

          // Handle different response types based on request
          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => true,
                  'message' => 'Program Studi berhasil ditambahkan',
                  'data' => $programStudi
              ], 201);
          }

          return redirect()->route('admin-program-studi-index')
                          ->with('success', 'Program Studi berhasil ditambahkan');

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
          Log::error('Error creating program studi: ' . $e->getMessage());

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
          $prodi = ProgramStudi::findOrFail($id);

          return view('pages.admin.programStudi.form', compact('prodi'));
      } catch (ModelNotFoundException $e) {
          return redirect()->route('admin-program-studi-index')
                          ->with('error', 'Program Studi tidak ditemukan');
      } catch (Exception $e) {
          Log::error('Error loading edit form: ' . $e->getMessage());
          return redirect()->route('admin-program-studi-index')
                          ->with('error', 'Terjadi kesalahan saat memuat form');
      }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      try {
          DB::beginTransaction();

          $programStudi = ProgramStudi::findOrFail($id);
          $validated = $this->validateProgramStudiData($request, $programStudi->id);

          $programStudi->update($validated);

          DB::commit();

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => true,
                  'message' => 'Program Studi berhasil diperbarui',
                  'data' => $programStudi->fresh()
              ]);
          }

          return redirect()->route('admin-program-studi-index')
                          ->with('success', 'Program Studi berhasil diperbarui');

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
                  'message' => 'Program Studi tidak ditemukan'
              ], 404);
          }

          return redirect()->route('admin-program-studi-index')
                          ->with('error', 'Program Studi tidak ditemukan');

      } catch (Exception $e) {
          DB::rollBack();
          Log::error('Error updating program studi: ' . $e->getMessage());

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

          $programStudi = ProgramStudi::findOrFail($id);

          // Check if Program Studi can be deleted
          $this->checkProgramStudiCanBeDeleted($programStudi);

          $programStudi->delete();

          DB::commit();

          return response()->json([
              'success' => true,
              'message' => 'Program Studi berhasil dihapus'
          ]);

      } catch (ModelNotFoundException $e) {
          DB::rollBack();
          return response()->json([
              'success' => false,
              'message' => 'Program Studi tidak ditemukan'
          ], 404);

      } catch (Exception $e) {
          DB::rollBack();
          Log::error('Error deleting program studi: ' . $e->getMessage());

          return response()->json([
              'success' => false,
              'message' => $e->getMessage()
          ], 400);
      }
    }
}
