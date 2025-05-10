<?php

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

Route::get('/dosen', function () {
  return new DosenCollection([]);
});

// Untuk mahasiswa
Route::get('/mahasiswa', function () {
  return new MahasiswaCollection([]);
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
