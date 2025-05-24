<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\ProgramStudi;

class MahasiswaController extends Controller
{
    private function validateMahasiswaData(Request $request, $id = null)
    {
      $nrpRule = $id ? "required|unique:mahasiswa,nrp,{$id}" : 'required|unique:mahasiswa,nrp';
      $emailRule = $id ? "required|email|unique:mahasiswa,email,{$id}" : 'required|email|unique:mahasiswa,email';

      return $request->validate([
          'prodi_id' => 'required|uuid|exists:program_studi,id',
          'kelas_id' => 'required|uuid|exists:kelas,id',
          'nrp' => $nrpRule,
          'nama' => 'required|string|max:100',
          'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
          'telepon' => 'nullable|string|max:15',
          'email' => $emailRule,
          'password' => $id ? 'nullable|string|min:8' : 'required|string|min:8',
          'agama' => 'required|string|max:20',
          'semester' => 'required|string|max:10',
          'tanggal_lahir' => 'required|date|before:today',
          'tanggal_masuk' => 'required|date|before_or_equal:today',
          'status' => ['required', Rule::in(['Aktif', 'Cuti', 'Keluar'])],
          'alamat_jalan' => 'nullable|string',
          'provinsi' => 'required|string|max:50',
          'kode_pos' => 'required|string|max:50',
          'negara' => 'required|string|max:50',
          'kelurahan' => 'required|string|max:50',
          'kecamatan' => 'required|string|max:50',
          'kota' => 'required|string|max:50',
      ], [
          'prodi_id.required' => 'Program studi harus dipilih.',
          'prodi_id.exists' => 'Program studi tidak valid.',
          'kelas_id.required' => 'Kelas harus dipilih.',
          'kelas_id.exists' => 'Kelas tidak valid.',
          'nrp.unique' => 'NRP sudah digunakan.',
          'email.unique' => 'Email sudah digunakan.',
          'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
          'tanggal_masuk.before_or_equal' => 'Tanggal masuk tidak boleh di masa depan.',
      ]);
    }

    public function getKelasByProdi($prodiId)
    {
      try {
          $kelas = Kelas::where('prodi_id', $prodiId)->get();

          return response()->json([
              'success' => true,
              'data' => $kelas
          ]);
      } catch (Exception $e) {
          return response()->json([
              'success' => false,
              'message' => 'Gagal mengambil data kelas'
          ], 500);
      }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.mahasiswa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ambil data program studi dan kelas
        $program_studi = ProgramStudi::all();
        $kelas = Kelas::all();

        // kirim data ke view
        return view('pages.admin.mahasiswa.form', compact('program_studi', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try {
        // Validate the request data
        $validated = $this->validateMahasiswaData($request);

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);

        // Create a new Mahasiswa record
        $mahasiswa = Mahasiswa::create($validated);

        // Handle different response types based on request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data mahasiswa berhasil disimpan.',
                'data' => $mahasiswa->load(['programStudi', 'kelas'])
            ], 201);
        }

        return redirect()->route('admin-mahasiswa-index')
            ->with('success', 'Data mahasiswa berhasil disimpan.');
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
        Log::error('Error creating mahasiswa: ' . $e->getMessage());

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
      $mahasiswa = Mahasiswa::findOrFail($id);

      $program_studi = ProgramStudi::all();
      $kelas = Kelas::all();

      // Pass the mahasiswa data to the view
      return view('pages.admin.mahasiswa.form', compact('mahasiswa', 'program_studi', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
      try {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Validate the request data
        $validated = $this->validateMahasiswaData($request, $id);

        // Only hash the password if it's provided
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update the Mahasiswa record
        $mahasiswa->update($validated);

        // Handle different response types based on request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data mahasiswa berhasil diperbarui.',
                'data' => $mahasiswa->fresh()->load(['programStudi', 'kelas'])
            ]);
        }

        return redirect()->route('admin-mahasiswa-index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
      } catch (ModelNotFoundException $e) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan.'
            ], 404);
        }
        abort(404);
      } catch (ValidationException $e) {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        throw $e;
      } catch (Exception $e) {
        Log::error('Error updating mahasiswa: ' . $e->getMessage());

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
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        // Handle different response types based on request
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data mahasiswa berhasil dihapus.'
            ]);
        }

        return redirect()->route('admin-mahasiswa-index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
      } catch (ModelNotFoundException $e) {
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan.'
            ], 404);
        }
        abort(404);
      } catch (Exception $e) {
        Log::error('Error deleting mahasiswa: ' . $e->getMessage());

        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data.'
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menghapus data.');
      }
    }
}
