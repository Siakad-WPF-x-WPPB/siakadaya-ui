<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\Dosen;
use App\Models\ProgramStudi;

class DosenController extends Controller
{
    private function validateDosenData(Request $request, $id = null)
    {
        $nipRule = $id ? "required|string|max:18|unique:dosen,nip,{$id}" : 'required|string|max:18|unique:dosen,nip';
        $emailRule = $id ? "required|email|unique:dosen,email,{$id}" : 'required|email|unique:dosen,email';

        return $request->validate([
            'prodi_id' => 'required|exists:program_studi,id',
            'nip' => $nipRule,
            'nama' => 'required|string',
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'telepon' => 'required|string',
            'email' => $emailRule,
            'password' => $id ? 'nullable|string' : 'required|string',
            'tanggal_lahir' => 'required|date|before:today',
            'jabatan' => 'required|string',
            'golongan_akhir' => 'required|string',
            'is_wali' => 'boolean',
        ], [
            'prodi_id.required' => 'Program studi harus dipilih.',
            'prodi_id.exists' => 'Program studi yang dipilih tidak valid.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.max' => 'NIP maksimal 18 karakter.',
            'nip.unique' => 'NIP sudah digunakan oleh dosen lain.',
            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.max' => 'Nama dosen maksimal 100 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
            'telepon.required' => 'Nomor telepon wajib diisi.',
            'telepon.max' => 'Nomor telepon maksimal 15 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh dosen lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'golongan_akhir.required' => 'Golongan akhir wajib diisi.',
            'is_wali.required' => 'Status wali harus dipilih.',
        ]);
    }

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
        $program_studi = ProgramStudi::all();

        return view('pages.admin.dosen.form', compact('program_studi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try {
        // validate the request data
        $validated = $this->validateDosenData($request);

        // hash the password
        $validated['password'] = bcrypt($validated['password']);

        // create a new Dosen record
        $dosen = Dosen::create($validated);

        // Handle different response types based on request
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil disimpan.',
                'data' => $dosen
            ], 201);
        }

        return redirect()->route('admin-dosen-index')->with('success', 'Data dosen berhasil disimpan.');
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
        $dosen = Dosen::findOrFail($id);
        $program_studi = ProgramStudi::all();

        return view('pages.admin.dosen.form', compact('dosen', 'program_studi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      try {
        $dosen = Dosen::findOrFail($id);

        // Use the existing validation method with custom error messages
        $validated = $this->validateDosenData($request, $id);

        // Handle password update
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            // Remove password from validated data if not provided
            unset($validated['password']);
        }

        $dosen->update($validated);

        // Handle different response types
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data dosen berhasil diperbarui.',
                'data' => $dosen
            ]);
        }

        return redirect()->route('admin-dosen-index')->with('success', 'Data dosen berhasil diperbarui.');
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
                  'message' => 'Data dosen tidak ditemukan'
              ], 404);
          }

          return redirect()->route('admin-dosen-index')
              ->with('error', 'Data dosen tidak ditemukan.');

      } catch (Exception $e) {
          Log::error('Error updating dosen: ' . $e->getMessage());

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
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return response()->json([
            'message' => 'Dosen berhasil dihapus'
        ]);
    }
}
