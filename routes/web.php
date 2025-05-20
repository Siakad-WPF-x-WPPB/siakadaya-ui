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

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

// * Admin Routes
// TODO: implement admin authentication
Route::prefix('admin')->group(function () {
  Route::get('/', [DashboardController::class, 'index'])->name('admin-dashboard');
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');
  Route::resource('mahasiswa', MahasiswaController::class)->names([
    'index'   => 'admin-mahasiswa-index',
    'create'  => 'admin-mahasiswa-create',
    'store'   => 'admin-mahasiswa-store',
    'show'    => 'admin-mahasiswa-show',
    'edit'    => 'admin-mahasiswa-edit',
    'update'  => 'admin-mahasiswa-update',
    'destroy' => 'admin-mahasiswa-destroy',
  ]);
  Route::resource('dosen', DosenController::class)->names([
    'index'   => 'admin-dosen-index',
    'create'  => 'admin-dosen-create',
    'store'   => 'admin-dosen-store',
    'show'    => 'admin-dosen-show',
    'edit'    => 'admin-dosen-edit',
    'update'  => 'admin-dosen-update',
    'destroy' => 'admin-dosen-destroy',
  ]);
  Route::resource('mata-kuliah', MataKuliahController::class)->names([
    'index'   => 'admin-mata-kuliah-index',
    'create'  => 'admin-mata-kuliah-create',
    'store'   => 'admin-mata-kuliah-store',
    'show'    => 'admin-mata-kuliah-show',
    'edit'    => 'admin-mata-kuliah-edit',
    'update'  => 'admin-mata-kuliah-update',
    'destroy' => 'admin-mata-kuliah-destroy',
  ]);
  Route::resource('kelas', KelasController::class)->names([
    'index'   => 'admin-kelas-index',
    'create'  => 'admin-kelas-create',
    'store'   => 'admin-kelas-store',
    'show'    => 'admin-kelas-show',
    'edit'    => 'admin-kelas-edit',
    'update'  => 'admin-kelas-update',
    'destroy' => 'admin-kelas-destroy',
  ]);
  Route::resource('tahun-ajar', TahunAjarController::class)->names([
    'index'   => 'admin-tahun-ajar-index',
    'create'  => 'admin-tahun-ajar-create',
    'store'   => 'admin-tahun-ajar-store',
    'show'    => 'admin-tahun-ajar-show',
    'edit'    => 'admin-tahun-ajar-edit',
    'update'  => 'admin-tahun-ajar-update',
    'destroy' => 'admin-tahun-ajar-destroy',
  ]);
  Route::resource('jadwal-kuliah', JadwalKuliahController::class)->names([
    'index'   => 'admin-jadwal-kuliah-index',
    'create'  => 'admin-jadwal-kuliah-create',
    'store'   => 'admin-jadwal-kuliah-store',
    'show'    => 'admin-jadwal-kuliah-show',
    'edit'    => 'admin-jadwal-kuliah-edit',
    'update'  => 'admin-jadwal-kuliah-update',
    'destroy' => 'admin-jadwal-kuliah-destroy',
  ]);
});

// Route::prefix('dosen')->name('dosen.')->group(function () {
//     Route::get('/login', [DosenLoginController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [DosenLoginController::class, 'login']);
//     Route::post('/logout', [DosenLoginController::class, 'logout'])->name('logout');

//     // Rute Dosen yang dilindungi
//     Route::middleware('auth:dosen')->group(function () {
//         Route::get('/dashboard', function () {
//             // dd(Auth::user()); // Pastikan instance Dosen
//             return view('dosen.dashboard');
//         })->name('dashboard');
//         // Rute dosen lainnya
//     });
// });
