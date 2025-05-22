<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MahasiswaAuthController;
use App\Http\Controllers\authentications\AdminLoginController;
use App\Http\Controllers\pages\admin\DosenController;
use App\Http\Controllers\pages\admin\JadwalKuliahController;
use App\Http\Controllers\pages\admin\KelasController;
use App\Http\Controllers\pages\admin\MahasiswaController;
use App\Http\Controllers\pages\admin\MataKuliahController;
use App\Http\Controllers\pages\admin\TahunAjarController;
use App\Http\Controllers\pages\mahasiswa\FrsMahasiswaController;
use App\Http\Controllers\pages\mahasiswa\JadwalKuliahMahasiswaController;
use App\Http\Resources\admin\DosenCollection;
use App\Http\Resources\admin\JadwalCollection;
use App\Http\Resources\admin\KelasCollection;
use App\Http\Resources\admin\MahasiswaCollection;
use App\Http\Resources\admin\MataKuliahCollection;
use App\Http\Resources\admin\TahunAjarCollection;
use App\Http\Resources\dosen\FrsCollection;

// * API Auth
// TODO: implementasi API Auth
// *********************************************************************************
Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::get('/login', function () {
    return response()->json(['message' => 'Please login'], 401);
})->name('login');

Route::post('/mahasiswa/login', [MahasiswaAuthController::class, 'login']);

// Rute ini akan menggunakan 'auth:sanctum'.
// Sanctum akan mengautentikasi token dan mengambil 'tokenable' model (yaitu Mahasiswa).
Route::middleware('auth:mahasiswa_api')->prefix('mahasiswa')->group(function () {
  Route::get('/profile', [MahasiswaAuthController::class, 'profile']);
  Route::post('/frs/store', [FrsMahasiswaController::class, 'store']);
  Route::post('/logout', [MahasiswaAuthController::class, 'logout']);
  Route::get('/jadwal', [JadwalKuliahMahasiswaController::class, 'semua']);
  Route::get('/jadwal/hari-ini', [JadwalKuliahMahasiswaController::class, 'hariIni']);
  Route::get('/jadwal/mendatang', [JadwalKuliahMahasiswaController::class, 'besok']);
  // Rute API mahasiswa lainnya
  Route::get('/data-khusus', function (Request $request) {
    // $request->user() di sini adalah instance Mahasiswa
    return response()->json(['message' => 'Ini data khusus untuk mahasiswa: ' . $request->user()->name]);
  });
});

// * API Mahasiswa
// TODO: implementasi API Mahasiswa
// *********************************************************************************

Route::get('/mahasiswa', function () {
  return new MahasiswaCollection([]);
});
Route::get('/mahasiswa/{id}', [MahasiswaController::class, 'show']);
Route::post('/mahasiswa/store', [MahasiswaController::class, 'store']);
Route::put('/mahasiswa/update/{id}', [MahasiswaController::class, 'update']);
Route::delete('/mahasiswa/destroy/{id}', [MahasiswaController::class, 'destroy']);

// * API Dosen
// TODO: implementasi API Dosen
// *********************************************************************************

Route::get('/dosen', function () {
  return new DosenCollection([]);
});
Route::get('/dosen/{id}', [DosenController::class, 'show']);
Route::post('/dosen/store', [DosenController::class, 'store']);
Route::put('/dosen/update/{id}', [DosenController::class, 'update']);
Route::delete('dosen/destroy/{id}', [DosenController::class, 'destroy']);

// * API Mata Kuliah
// TODO: implementasi API Mata Kuliah
// *********************************************************************************

Route::get('/mata-kuliah', function () {
  return new MataKuliahCollection([]);
});
Route::get('/mata-kuliah/{id}', [MataKuliahController::class, 'show']);
Route::post('/mata-kuliah/store', [MataKuliahController::class, 'store']);
Route::put('/mata-kuliah/update/{id}', [MataKuliahController::class, 'update']);
Route::delete('/mata-kuliah/destroy/{id}', [MataKuliahController::class, 'destroy']);

// * API Kelas
// TODO: implementasi API Kelas
// *********************************************************************************

Route::get('/kelas', function () {
  return new KelasCollection([]);
});
Route::get('/kelas/{id}', [KelasController::class, 'show']);
Route::post('/kelas/store', [KelasController::class, 'store']);
Route::put('/kelas/update/{id}', [KelasController::class, 'update']);
Route::delete('/kelas/destroy/{id}', [KelasController::class, 'destroy']);

// * API Jadwal Kuliah
// TODO: implementasi API Jadwal Kuliah
// *********************************************************************************

Route::get('/jadwal-kuliah', function () {
  return new JadwalCollection([]);
});
Route::get('/jadwal-kuliah/{id}', [JadwalKuliahController::class, 'show']);
Route::post('/jadwal-kuliah/store', [JadwalKuliahController::class, 'store']);
Route::put('/jadwal-kuliah/update/{id}', [JadwalKuliahController::class, 'update']);
Route::delete('jadwal-kuliah/destroy/{id}', [JadwalKuliahController::class, 'destroy']);

// * API Tahun Ajar
// TODO: implementasi API Tahun Ajar
// *********************************************************************************

Route::get('/tahun-ajar', function () {
  return new TahunAjarCollection([]);
});
Route::get('/tahun-ajar/{id}', [TahunAjarController::class, 'show']);
Route::post('/tahun-ajar/store', [TahunAjarController::class, 'store']);
Route::put('/tahun-ajar/update/{id}', [TahunAjarController::class, 'update']);
Route::delete('/tahun-ajar/destroy/{id}', [TahunAjarController::class, 'destroy']);

// * API FRS
// TODO: implementasi API FRS
// *********************************************************************************
Route::get('/frs', function () {
  return new FrsCollection([]);
});