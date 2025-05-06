<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

use App\Http\Controllers\pages\admin\{
  DashboardController,
  MahasiswaController,
  DosenController,
};

// Main Page Route
Route::get('/', [DashboardController::class, 'index'])->name('pages-admin-dashboard');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('pages-admin-dashboard');
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('pages-admin-mahasiswa');
Route::get('/dosen', [DosenController::class, 'index'])->name('pages-admin-dosen');

// Testing Get Data
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
  return response()->json([
    'data' => \App\Models\Kelas::all()
  ]);
});

Route::get('/dosen', function () {
  return response()->json([
    'data' => \App\Models\Dosen::all()
  ]);
});

Route::get('/mahasiswa', function () {
  return response()->json([
    'data' => \App\Models\Mahasiswa::all()
  ]);
});

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
