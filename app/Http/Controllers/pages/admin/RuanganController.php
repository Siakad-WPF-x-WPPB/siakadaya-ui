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

use App\Models\Ruangan;

class RuanganController extends Controller
{
    private function validateRuanganData(Request $request, $id = null)
    {
        $rules = [
            'kode' => [
                'required',
                'string',
                'max:25',
                'regex:/^[A-Z0-9\-]+$/', // Allow uppercase letters, numbers, and hyphens
                Rule::unique('ruangan', 'kode')->ignore($id)
            ],
            'nama' => [
                'required',
                'string',
                'max:100',
                'min:3'
            ],
            'gedung' => [
                'required',
                'string',
                'max:100',
                'min:1'
            ]
        ];

        $messages = [
            'kode.required' => 'Kode ruangan harus diisi',
            'kode.max' => 'Kode ruangan maksimal 25 karakter',
            'kode.regex' => 'Kode ruangan hanya boleh berisi huruf kapital, angka, dan tanda hubung',
            'kode.unique' => 'Kode ruangan sudah digunakan',
            'nama.required' => 'Nama ruangan harus diisi',
            'nama.max' => 'Nama ruangan maksimal 100 karakter',
            'nama.min' => 'Nama ruangan minimal 3 karakter',
            'gedung.required' => 'Nama gedung harus diisi',
            'gedung.max' => 'Nama gedung maksimal 100 karakter',
            'gedung.min' => 'Nama gedung minimal 1 karakter'
        ];

        $validated = $request->validate($rules, $messages);

        $this->validateRuanganBusinessRules($validated, $id);

        return $validated;
    }

    private function validateRuanganBusinessRules($data, $excludeId = null)
    {
        $conflicts = [];

        // Check if nama already exists in the same gedung (case insensitive)
        $namaExists = Ruangan::whereRaw('LOWER(nama) = ? AND LOWER(gedung) = ?', [
                strtolower($data['nama']),
                strtolower($data['gedung'])
            ])
            ->when($excludeId, function($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->exists();

        if ($namaExists) {
            $conflicts['nama'] = 'Nama ruangan sudah ada di gedung yang sama';
        }

        if (!empty($conflicts)) {
            throw ValidationException::withMessages($conflicts);
        }
    }

    private function checkRuanganCanBeDeleted($ruangan)
    {
        $relatedData = [];

        // Check if has related Jadwal
        $jadwalCount = $ruangan->jadwal()->count();
        if ($jadwalCount > 0) {
            $relatedData[] = "{$jadwalCount} jadwal kuliah";
        }

        if (!empty($relatedData)) {
            throw new Exception(
                'Ruangan tidak dapat dihapus karena masih memiliki: ' .
                implode(', ', $relatedData) . '. Hapus data terkait terlebih dahulu.'
            );
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.ruangan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      try {
          return view('pages.admin.ruangan.form');
      } catch (Exception $e) {
          Log::error('Error loading create form: ' . $e->getMessage());
          return redirect()->route('admin-ruangan-index')->with('error', 'Terjadi kesalahan saat memuat form');
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
          $validated = $this->validateRuanganData($request);

          // Create a new Ruangan record
          $ruangan = Ruangan::create($validated);

          // Commit the transaction
          DB::commit();

          // Handle different response types based on request
          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => true,
                  'message' => 'Ruangan berhasil ditambahkan',
                  'data' => $ruangan
              ], 201);
          }

          return redirect()->route('admin-ruangan-index')->with('success', 'Ruangan berhasil ditambahkan');
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
          Log::error('Error creating ruangan: ' . $e->getMessage());

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
          $ruangan = Ruangan::findOrFail($id);

          return view('pages.admin.ruangan.form', compact('ruangan'));
      } catch (ModelNotFoundException $e) {
          return redirect()->route('admin-ruangan-index')
                          ->with('error', 'Ruangan tidak ditemukan');
      } catch (Exception $e) {
          Log::error('Error loading edit form: ' . $e->getMessage());
          return redirect()->route('admin-ruangan-index')
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

          $ruangan = Ruangan::findOrFail($id);
          $validated = $this->validateRuanganData($request, $ruangan->id);

          $ruangan->update($validated);

          DB::commit();

          if ($request->expectsJson() || $request->is('api/*')) {
              return response()->json([
                  'success' => true,
                  'message' => 'Ruangan berhasil diperbarui',
                  'data' => $ruangan->fresh()
              ]);
          }

          return redirect()->route('admin-ruangan-index')
                          ->with('success', 'Ruangan berhasil diperbarui');

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
                  'message' => 'Ruangan tidak ditemukan'
              ], 404);
          }

          return redirect()->route('admin-ruangan-index')
                          ->with('error', 'Ruangan tidak ditemukan');

      } catch (Exception $e) {
          DB::rollBack();
          Log::error('Error updating ruangan: ' . $e->getMessage());

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

          $ruangan = Ruangan::findOrFail($id);

          // Check if Ruangan can be deleted
          $this->checkRuanganCanBeDeleted($ruangan);

          $ruangan->delete();

          DB::commit();

          return response()->json([
              'success' => true,
              'message' => 'Ruangan berhasil dihapus'
          ]);

      } catch (ModelNotFoundException $e) {
          DB::rollBack();
          return response()->json([
              'success' => false,
              'message' => 'Ruangan tidak ditemukan'
          ], 404);

      } catch (Exception $e) {
          DB::rollBack();
          Log::error('Error deleting ruangan: ' . $e->getMessage());

          return response()->json([
              'success' => false,
              'message' => $e->getMessage()
          ], 400);
      }
    }
}
