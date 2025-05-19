<?php

use App\Http\Controllers\authentications\AdminLoginController;
use App\Http\Controllers\authentications\DosenLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;

use App\Http\Controllers\pages\admin\{
  DashboardController,
  DosenController,
  JadwalKuliahController,
  KelasController,
  MahasiswaController,
  MataKuliahController,
  TahunAjarController
};

// Main Page Route
Route::get('/', [DashboardController::class, 'index'])->name('admin-dashboard');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

// Admin Routes
Route::prefix('admin')->group(function () {
  Route::resource('dosen', DosenController::class);
  Route::resource('kelas', KelasController::class);
});

Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/login', [DosenLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [DosenLoginController::class, 'login']);
    Route::post('/logout', [DosenLoginController::class, 'logout'])->name('logout');

    // Rute Dosen yang dilindungi
    Route::middleware('auth:dosen')->group(function () {
        Route::get('/dashboard', function () {
            // dd(Auth::user()); // Pastikan instance Dosen
            return view('dosen.dashboard'); // resources/views/dosen/dashboard.blade.php
        })->name('dashboard');
        // Rute dosen lainnya
    });
});

Route::get('admin/jadwal-kuliah', [JadwalKuliahController::class, 'index'])->name('admin-jadwal-kuliah');
// Route::get('admin/kelas', [KelasController::class, 'index'])->name('admin-kelas');
Route::get('admin/mahasiswa', [MahasiswaController::class, 'index'])->name('admin-mahasiswa');
Route::get('admin/mata-kuliah', [MataKuliahController::class, 'index'])->name('admin-mata-kuliah');
Route::get('admin/tahun-ajar', [TahunAjarController::class, 'index'])->name('admin-tahun-ajar');
