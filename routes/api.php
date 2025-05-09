<?php

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

Route::get('/tahun-ajar', function () {
  return new TahunAjarCollection([]);
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

Route::get('/mahasiswa', function () {
  return new MahasiswaCollection([]);
});

Route::get('/matakuliah', function () {
  return new MataKuliahCollection([]);
});

Route::get('/jadwal', function () {
  return new JadwalCollection([]);
});
