<?php

use App\Http\Resources\admin\DosenCollection;
use App\Http\Resources\admin\KelasCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Resources\admin\MahasiswaCollection;
use App\Http\Resources\admin\MataKuliahCollection;

Route::get('/user', function (Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::get('/tahun-ajar', function () {
  return response()->json([
    'data' => \App\Models\TahunAjar::all()
  ]);
});

Route::get('/jurusan', function () {
  return response()->json([
    'data' => \App\Models\Jurusan::all()
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
