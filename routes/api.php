<?php

use App\Http\Controllers\Api\MahasiswaAuthController;
use App\Http\Controllers\authentications\AdminLoginController;
use App\Http\Controllers\authentications\DosenLoginController;
use App\Http\Controllers\pages\admin\DosenController;
use App\Http\Controllers\pages\admin\JadwalKuliahController;
use App\Http\Controllers\pages\admin\KelasController;
use App\Http\Controllers\pages\admin\MahasiswaController;
use App\Http\Controllers\pages\admin\MataKuliahController;
use App\Http\Controllers\pages\admin\TahunAjarController;
use App\Http\Resources\admin\DosenCollection;
use App\Http\Resources\admin\JadwalCollection;
use App\Http\Resources\admin\KelasCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\admin\MahasiswaCollection;
use App\Http\Resources\admin\MataKuliahCollection;
use App\Http\Resources\admin\TahunAjarCollection;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

// Untuk tahun ajar
Route::get('/tahun-ajar', function () {
  return new TahunAjarCollection([]);
});

Route::prefix('admin')->group(function () {
  Route::post('/tahun-ajar', [TahunAjarController::class, 'store']);
  Route::put('/tahun-ajar/{id}', [TahunAjarController::class, 'update']);
  Route::delete('/tahun-ajar/{id}', [TahunAjarController::class, 'destroy']);
});

Route::get('/jurusan', function () {
  return response()->json([
    'data' => \App\Models\ProgramStudi::all()
  ]);
});
Route::get('/ruangan', function () {
  return response()->json([
    'data' => \App\Models\Ruangan::all()
  ]);
});

Route::get('/kelas', function () {
  return new KelasCollection([]);
});
Route::post('/kelas/store', [KelasController::class, 'store']);
Route::put('/kelas/update/{id}', [KelasController::class, 'update']);
Route::delete('kelas/destroy/{id}', [KelasController::class, 'destroy']);

// Dosen API & Testing
Route::get('/dosen', function () {
  return new DosenCollection([]);
});
Route::post('/dosen/store', [DosenController::class, 'store']);
Route::put('/dosen/update/{id}', [DosenController::class, 'update']);
Route::delete('dosen/destroy/{id}', [DosenController::class, 'destroy']);

// Untuk mahasiswa
Route::get('/mahasiswa', function () {
  return new MahasiswaCollection([]);
});

Route::post('/mahasiswa/login', [MahasiswaAuthController::class, 'login']);

// Rute ini akan menggunakan 'auth:sanctum'.
// Sanctum akan mengautentikasi token dan mengambil 'tokenable' model (yaitu Mahasiswa).
Route::middleware('auth:sanctum')->prefix('mahasiswa')->group(function () {
  Route::get('/profile', [MahasiswaAuthController::class, 'profile']);
  Route::post('/logout', [MahasiswaAuthController::class, 'logout']);
  // Rute API mahasiswa lainnya
  Route::get('/data-khusus', function (Request $request) {
    // $request->user() di sini adalah instance Mahasiswa
    return response()->json(['message' => 'Ini data khusus untuk mahasiswa: ' . $request->user()->name]);
  });
});

Route::prefix('admin')->group(function () {
  Route::post('/mahasiswa', [MahasiswaController::class, 'store']);
  Route::put('/mahasiswa/{id}', [MahasiswaController::class, 'update']);
  Route::delete('/mahasiswa/{id}', [MahasiswaController::class, 'destroy']);
});


// Untuk matakuliah
Route::get('/matakuliah', function () {
  return new MataKuliahCollection([]);
});
Route::prefix('admin')->group(function () {
  Route::post('/matakuliah', [MataKuliahController::class, 'store']);
  Route::put('/matakuliah/{id}', [MatakuliahController::class, 'update']);
  Route::delete('/matakuliah/{id}', [MatakuliahController::class, 'destroy']);
});


Route::get('/jadwal', function () {
  return new JadwalCollection([]);
});
Route::post('/jadwal/store', [JadwalKuliahController::class, 'store']);
Route::put('/jadwal/update/{id}', [JadwalKuliahController::class, 'update']);
Route::delete('jadwal/destroy/{id}', [JadwalKuliahController::class, 'destroy']);

// Rute Autentikasi Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Rute Admin yang dilindungi
    Route::middleware('auth:admin')->group(function () { // Menggunakan middleware 'auth:nama_guard'
        Route::get('/dashboard', function () {
            // Pastikan Auth::user() mengembalikan instance Admin
            // dd(Auth::user());
            return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
        })->name('dashboard');
        // Rute admin lainnya
    });
});

// Rute Autentikasi Dosen
