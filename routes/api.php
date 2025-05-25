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
use App\Http\Controllers\pages\admin\ProgramStudiController;
use App\Http\Controllers\pages\admin\RuanganController;

use App\Http\Controllers\pages\mahasiswa\FrsMahasiswaController;
use App\Http\Controllers\pages\mahasiswa\JadwalKuliahMahasiswaController;

use App\Http\Resources\admin\DosenCollection;
use App\Http\Resources\admin\JadwalCollection;
use App\Http\Resources\admin\KelasCollection;
use App\Http\Resources\admin\MahasiswaCollection;
use App\Http\Resources\admin\MataKuliahCollection;
use App\Http\Resources\admin\TahunAjarCollection;
use App\Http\Resources\admin\ProgramStudiCollection;
use App\Http\Resources\admin\RuanganCollection;

use App\Http\Resources\dosen\DosenJadwalKuliahCollection;
use App\Http\Resources\dosen\FrsCollection;
use App\Http\Resources\dosen\FrsDetailCollection;
use App\Http\Resources\dosen\NilaiCollection;

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

// * API User Mahasiswa
// TODO: implementasi API Mahasiswa
// *********************************************************************************
// Rute ini akan menggunakan 'auth:sanctum'.
// Sanctum akan mengautentikasi token dan mengambil 'tokenable' model (yaitu Mahasiswa).
Route::middleware('auth:mahasiswa_api')->prefix('mahasiswa')->group(function () {
  Route::get('/profile', [MahasiswaAuthController::class, 'profile']);
  Route::post('/frs/store', [FrsMahasiswaController::class, 'store']);
  Route::post('/logout', [MahasiswaAuthController::class, 'logout']);
  Route::get('/jadwal', [JadwalKuliahMahasiswaController::class, 'getAll']);
  Route::get('/jadwal/today', [JadwalKuliahMahasiswaController::class, 'getToday']);
  Route::get('/jadwal/tomorrow', [JadwalKuliahMahasiswaController::class, 'getTomorrow']);
  Route::get('/jadwal/program-studi', [JadwalKuliahMahasiswaController::class, 'getPerProdi']);

  // Rute untuk mendapatkan data FRS mahasiswa
  Route::get('/jadwal/dropdown-options', [JadwalKuliahMahasiswaController::class, 'getDropdownOptions']);
  Route::get('/jadwal/dropdown-options-prodi', [JadwalKuliahMahasiswaController::class, 'getDropdownOptionsProdi']);

  // Rute API mahasiswa lainnya
  Route::get('/data-khusus', function (Request $request) {
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

// * API Dosen
// TODO: implementasi API Dosen
// *********************************************************************************

Route::get('/dosen', function () {
  return new DosenCollection([]);
});
Route::get('/dosen/{id}', [DosenController::class, 'show']);
Route::get('/dosen/{id}', [DosenController::class, 'show']);
Route::post('/dosen/store', [DosenController::class, 'store']);
Route::put('/dosen/update/{id}', [DosenController::class, 'update']);
Route::delete('/dosen/destroy/{id}', [DosenController::class, 'destroy']);

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

Route::get('/jadwal', function () {
  return new JadwalCollection([]);
});
Route::get('/jadwal/{id}', [JadwalKuliahController::class, 'show']);
Route::post('/jadwal/store', [JadwalKuliahController::class, 'store']);
Route::put('/jadwal/update/{id}', [JadwalKuliahController::class, 'update']);
Route::delete('/jadwal/destroy/{id}', [JadwalKuliahController::class, 'destroy']);

// * API Dosen Jadwal Kuliah
// TODO: implementasi API Dosen Jadwal Kuliah
// *********************************************************************************
Route::get('/dosen/jadwal-kuliah', function () {
  return new DosenJadwalKuliahCollection([]);
});

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
// TODO: implementasi API Program Studi
// *********************************************************************************
Route::get('/program-studi', function () {
  return new ProgramStudiCollection([]);
});
Route::get('/program-studi/{id}', [ProgramStudiController::class, 'show']);
Route::post('/program-studi/store', [ProgramStudiController::class, 'store']);
Route::put('/program-studi/update/{id}', [ProgramStudiController::class, 'update']);
Route::delete('/program-studi/destroy/{id}', [ProgramStudiController::class, 'destroy']);

// * API FRS
// TODO: implementasi API Ruangan
// *********************************************************************************
Route::get('/ruangan', function () {
  return new RuanganCollection([]);
});
Route::get('/ruangan/{id}', [RuanganController::class, 'show']);
Route::post('/ruangan/store', [RuanganController::class, 'store']);
Route::put('/ruangan/update/{id}', [RuanganController::class, 'update']);
Route::delete('/ruangan/destroy/{id}', [RuanganController::class, 'destroy']);

// * API FRS Mahasiswa
// TODO: implementasi API FRS Mahasiswa
// *********************************************************************************
Route::get('/frs', function () {
  return new FrsCollection([]);
});

Route::get('/frs/detail', function () {
  return new FrsDetailCollection([]);
});



