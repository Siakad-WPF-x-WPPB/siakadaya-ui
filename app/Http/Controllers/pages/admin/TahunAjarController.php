<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\TahunAjar;

class TahunAjarController extends Controller
{
    private function validateTahunAjarData(Request $request, $id = null)
    {
      return $request->validate([
          'semester' => [
              'required',
              'string',
              'max:6',
              Rule::in(['Ganjil', 'Genap'])
          ],
          'tahun_mulai' => [
              'required',
              'integer',
              'min:1900',
              'max:' . (date('Y') + 10),
          ],
          'tahun_akhir' => [
              'required',
              'integer',
              'min:1900',
              'max:' . (date('Y') + 10),
              'gt:tahun_mulai'
          ],
          'status' => [
              'required',
              Rule::in(['Aktif', 'Tidak Aktif'])
          ],
          'mulai_frs' => ['nullable', 'date'],
          'selesai_frs' => ['nullable', 'date', 'after_or_equal:mulai_frs'],
          'mulai_edit_frs' => ['nullable', 'date', 'after_or_equal:selesai_frs'],
          'selesai_edit_frs' => ['nullable', 'date', 'after_or_equal:mulai_edit_frs'],
          'mulai_drop_frs' => ['nullable', 'date', 'after_or_equal:selesai_edit_frs'],
          'selesai_drop_frs' => ['nullable', 'date', 'after_or_equal:mulai_drop_frs'],
      ], [
          'semester.required' => 'Semester harus dipilih.',
          'semester.in' => 'Semester harus Ganjil atau Genap.',
          'semester.max' => 'Semester maksimal 6 karakter.',
          'tahun_mulai.required' => 'Tahun mulai wajib diisi.',
          'tahun_mulai.integer' => 'Tahun mulai harus berupa angka.',
          'tahun_mulai.min' => 'Tahun mulai minimal 1900.',
          'tahun_mulai.max' => 'Tahun mulai tidak boleh lebih dari ' . (date('Y') + 10) . '.',
          'tahun_akhir.required' => 'Tahun berakhir wajib diisi.',
          'tahun_akhir.integer' => 'Tahun berakhir harus berupa angka.',
          'tahun_akhir.min' => 'Tahun berakhir minimal 1900.',
          'tahun_akhir.max' => 'Tahun berakhir tidak boleh lebih dari ' . (date('Y') + 10) . '.',
          'tahun_akhir.gt' => 'Tahun berakhir harus lebih besar dari tahun mulai.',
          'status.required' => 'Status harus dipilih.',
          'status.in' => 'Status harus Aktif atau Tidak Aktif.',
          'mulai_frs.date' => 'Format tanggal mulai FRS tidak valid.',
          'selesai_frs.date' => 'Format tanggal selesai FRS tidak valid.',
          'selesai_frs.after_or_equal' => 'Tanggal selesai FRS harus setelah atau sama dengan tanggal mulai FRS.',
          'mulai_edit_frs.date' => 'Format tanggal mulai edit FRS tidak valid.',
          'mulai_edit_frs.after_or_equal' => 'Tanggal mulai edit FRS harus setelah atau sama dengan tanggal selesai FRS.',
          'selesai_edit_frs.date' => 'Format tanggal selesai edit FRS tidak valid.',
          'selesai_edit_frs.after_or_equal' => 'Tanggal selesai edit FRS harus setelah atau sama dengan tanggal mulai edit FRS.',
          'mulai_drop_frs.date' => 'Format tanggal mulai drop FRS tidak valid.',
          'mulai_drop_frs.after_or_equal' => 'Tanggal mulai drop FRS harus setelah atau sama dengan tanggal selesai edit FRS.',
          'selesai_drop_frs.date' => 'Format tanggal selesai drop FRS tidak valid.',
          'selesai_drop_frs.after_or_equal' => 'Tanggal selesai drop FRS harus setelah atau sama dengan tanggal mulai drop FRS.',
      ]);
    }

    private function validateUniqueActiveStatus(Request $request, $id = null)
    {
        if ($request->status === 'Aktif') {
            $query = TahunAjar::where('status', 'Aktif');
            if ($id) {
                $query->where('id', '!=', $id);
            }

            if ($query->exists()) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya satu tahun ajar yang dapat aktif pada satu waktu.'
                ]);
            }
        }
    }

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
      try {
        // validate the request data
        $validated = $this->validateTahunAjarData($request);

        // validate unique active status
        $this->validateUniqueActiveStatus($request);

        // If setting as active, deactivate others first
        if ($validated['status'] === 'Aktif') {
            TahunAjar::where('status', 'Aktif')->update(['status' => 'Tidak Aktif']);
        }

        // create a new tahun ajar record
        $tahunAjar = TahunAjar::create($validated);

        // handle different response based on request type
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data tahun ajar berhasil disimpan.',
                'data' => $tahunAjar
            ], 201);
        }

        return redirect()->route('admin-tahun-ajar-index')->with('success', 'Data tahun ajar berhasil disimpan.');
      } catch (ValidationException $e) {
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
        Log::error('Error creating tahun ajar: ' . $e->getMessage());

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
        $tahunAjar = TahunAjar::findOrFail($id);

        return view('pages.admin.tahunAjar.form', compact('tahunAjar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      try {
        // validate the request data
        $validated = $this->validateTahunAjarData($request, $id);

        // validate unique active status
        $this->validateUniqueActiveStatus($request, $id);

        // find the existing tahun ajar record
        $tahunAjar = TahunAjar::findOrFail($id);

        // If setting as active, deactivate others first
        if ($validated['status'] === 'Aktif') {
            TahunAjar::where('status', 'Aktif')
                ->where('id', '!=', $id)
                ->update(['status' => 'Tidak Aktif']);
        }

        // update the tahun ajar record
        $tahunAjar->update($validated);

        // handle different response based on request type
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data tahun ajar berhasil diperbarui.',
                'data' => $tahunAjar
            ]);
        }

        return redirect()->route('admin-tahun-ajar-index')->with('success', 'Data tahun ajar berhasil diperbarui.');
      } catch (ValidationException $e) {
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
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        return redirect()->back()
            ->with('error', 'Data tidak ditemukan.');
      } catch (Exception $e) {
        Log::error('Error updating tahun ajar: ' . $e->getMessage());

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
      try {
          $tahunAjar = TahunAjar::findOrFail($id);

          // Check if this academic year is used in jadwal
          if ($tahunAjar->jadwal()->exists()) {
              return response()->json([
                  'success' => false,
                  'message' => 'Tahun ajar tidak dapat dihapus karena masih digunakan dalam jadwal.'
              ], 422);
          }

          $tahunAjar->delete();

          return response()->json([
              'success' => true,
              'message' => 'Data tahun ajar berhasil dihapus.'
          ]);

      } catch (ModelNotFoundException $e) {
          return response()->json([
              'success' => false,
              'message' => 'Data tahun ajar tidak ditemukan.'
          ], 404);

      } catch (Exception $e) {
          Log::error('Error deleting tahun ajar: ' . $e->getMessage());

          return response()->json([
              'success' => false,
              'message' => 'Terjadi kesalahan saat menghapus data.'
          ], 500);
      }
    }
}
