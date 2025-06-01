<?php

namespace App\Http\Controllers\pages\admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PengumumanController extends Controller
{
    public function validatePengumumanData(Request $request, $id = null)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_dibuat' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'judul.max' => 'Judul pengumuman maksimal 255 karakter.',
            'isi.required' => 'Isi pengumuman wajib diisi.',
            'tanggal_dibuat.required' => 'Tanggal dibuat wajib diisi.',
            'status.required' => 'Status pengumuman harus dipilih.',
        ]);

        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            throw new Exception('Admin tidak terautentikasi');
        }

        $validated['admin_id'] = $admin->id;

        return $validated;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.admin.pengumuman.index');
    }

    public function create(Request $request)
    {
        return view('pages.admin.pengumuman.form');
    }

    public function store(Request $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Validate the request data
            $validated = $this->validatePengumumanData($request);

            // dd($validated);

            // Create a new pengumuman record
            $pengumuman = Pengumuman::create($validated);

            // Commit the transaction
            DB::commit();

            // Handle different response types based on request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengumuman berhasil ditambahkan',
                    'data' => $pengumuman
                ], 201);
            }

            return redirect()->route('admin-pengumuman-index')
                ->with('success', 'Pengumuman berhasil ditambahkan');
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
            Log::error('Error creating pengumuman: ' . $e->getMessage());

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

    public function edit(string $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('pages.admin.pengumuman.form', compact('pengumuman'));
    }

    public function update(Request $request, string $id)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Validate the request data
            $validated = $this->validatePengumumanData($request, $id);

            // Find the pengumuman record
            $pengumuman = Pengumuman::findOrFail($id);

            // Update the pengumuman record
            $pengumuman->update($validated);

            // Commit the transaction
            DB::commit();

            // Handle different response types based on request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengumuman berhasil diperbarui',
                    'data' => $pengumuman
                ], 200);
            }

            return redirect()->route('admin-pengumuman-index')
                ->with('success', 'Pengumuman berhasil diperbarui');
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
            Log::error('Error updating pengumuman: ' . $e->getMessage());

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

    public function destroy(string $id)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Find the pengumuman record
            $pengumuman = Pengumuman::findOrFail($id);

            // Delete the pengumuman record
            $pengumuman->delete();

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dihapus'
            ], 200);
        } catch (Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();
            Log::error('Error deleting pengumuman: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pengumuman'
            ], 500);
        }
    }
}
