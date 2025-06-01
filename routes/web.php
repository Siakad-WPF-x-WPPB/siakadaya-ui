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
    PengumumanController,
    TahunAjarController,
  ProgramStudiController,
  RuanganController,
};
use App\Http\Controllers\pages\dosen\DosenDashboardController;
use App\Http\Controllers\pages\dosen\DosenFrsController;
use App\Http\Controllers\pages\dosen\DosenJadwalKuliahController;
use App\Http\Controllers\pages\dosen\DosenMahasiswaController;
use App\Http\Controllers\pages\dosen\DosenNilaiController;
use App\Http\Controllers\pages\dosen\DosenProfileController;
use App\Http\Resources\dosen\DosenJadwalKuliahCollection;
use App\Http\Resources\dosen\NilaiCollection;
use App\Models\Jadwal;
use App\Models\Nilai;

// Main Page Route

// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');

// authentication
Route::get('/', [DosenLoginController::class, 'showLoginForm'])->name('login-dosen-view')->middleware('guest:dosen', 'guest:admin');
Route::post('/', [DosenLoginController::class, 'login'])->name('login-dosen');

Route::get('/admin', [AdminLoginController::class, 'showLoginForm'])->name('login-admin-view')->middleware('guest:dosen', 'guest:admin');
Route::post('/admin', [AdminLoginController::class, 'login'])->name('login-admin');

// * Admin Routes
// TODO: implement admin authentication
Route::prefix('admin')->group(function () {
  Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin-dashboard');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin-logout');
    Route::resource('mahasiswa', MahasiswaController::class)->names([
      'index'   => 'admin-mahasiswa-index',
      'create'  => 'admin-mahasiswa-create',
      'store'   => 'admin-mahasiswa-store',
      'show'    => 'admin-mahasiswa-show',
      'edit'    => 'admin-mahasiswa-edit',
      'update'  => 'admin-mahasiswa-update',
      'destroy' => 'admin-mahasiswa-destroy',
    ]);
    Route::get('/mahasiswa/kelas-by-prodi/{prodiId}', [MahasiswaController::class, 'getKelasByProdi'])->name('admin-mahasiswa-kelas-by-prodi');
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
    Route::get('/jadwal-kuliah/kelas-by-prodi/{prodiId}', [JadwalKuliahController::class, 'getKelasByProdi'])->name('admin-jadwal-kuliah-kelas-by-prodi');
    Route::get('/jadwal-kuliah/matakuliah-by-prodi/{prodiId}', [JadwalKuliahController::class, 'getMatakuliahByProdi'])->name('admin-jadwal-kuliah-matakuliah-by-prodi');
    Route::resource('jadwal-kuliah', JadwalKuliahController::class)->names([
      'index'   => 'admin-jadwal-kuliah-index',
      'create'  => 'admin-jadwal-kuliah-create',
      'store'   => 'admin-jadwal-kuliah-store',
      'show'    => 'admin-jadwal-kuliah-show',
      'edit'    => 'admin-jadwal-kuliah-edit',
      'update'  => 'admin-jadwal-kuliah-update',
      'destroy' => 'admin-jadwal-kuliah-destroy',
    ]);
    Route::resource('program-studi', ProgramStudiController::class)->names([
      'index'   => 'admin-program-studi-index',
      'create'  => 'admin-program-studi-create',
      'store'   => 'admin-program-studi-store',
      'show'    => 'admin-program-studi-show',
      'edit'    => 'admin-program-studi-edit',
      'update'  => 'admin-program-studi-update',
      'destroy' => 'admin-program-studi-destroy',
    ]);
    Route::resource('ruangan', RuanganController::class)->names([
      'index'   => 'admin-ruangan-index',
      'create'  => 'admin-ruangan-create',
      'store'   => 'admin-ruangan-store',
      'show'    => 'admin-ruangan-show',
      'edit'    => 'admin-ruangan-edit',
      'update'  => 'admin-ruangan-update',
      'destroy' => 'admin-ruangan-destroy',
    ]);
    Route::resource('pengumuman', PengumumanController::class)->names([
      'index'   => 'admin-pengumuman-index',
      'create'  => 'admin-pengumuman-create',
      'store'   => 'admin-pengumuman-store',
      'show'    => 'admin-pengumuman-show',
      'edit'    => 'admin-pengumuman-edit',
      'update'  => 'admin-pengumuman-update',
      'destroy' => 'admin-pengumuman-destroy',
    ]);
  });
});


// * Dosen Routes
// TODO: implement dosen authentication
Route::prefix('dosen')->group(
  function () {
    Route::middleware('auth:dosen')->group(
      function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dosen-dashboard');
        Route::get('/profile', [DosenProfileController::class, 'index'])->name('dosen-profile');
        Route::post('/logout', [DosenLoginController::class, 'logout'])->name('dosen-logout');
        Route::get('/mahasiswa', [DosenMahasiswaController::class, 'index'])->name('dosen-mahasiswa-index');
        Route::get('/jadwal-kuliah', [DosenJadwalKuliahController::class, 'index'])->name('dosen-jadwal-kuliah-index');
        Route::get('/jadwal-kuliah/{jadwal}/mahasiswa', [DosenJadwalKuliahController::class, 'listMahasiswa'])->name('dosen.jadwal.mahasiswa');

        Route::get('/jadwal-kuliah/{jadwal}/nilai/{mahasiswa}', [DosenNilaiController::class, 'create'])->name('dosen.nilai.create');
        Route::post('/jadwal-kuliah/{jadwal}/nilai/{mahasiswa}', [DosenNilaiController::class, 'store'])->name('dosen.nilai.store');
        Route::post('jadwal/{jadwal}/nilai/import', [DosenNilaiController::class, 'import'])->name('dosen.nilai.import');
        Route::get('jadwal/{jadwal}/nilai/template', [DosenNilaiController::class, 'downloadTemplate'])->name('dosen.nilai.template');

        // Route FRS
        Route::get('/frs', [DosenFrsController::class, 'index'])->name('dosen-frs-index');
        Route::get('/frs/data', [DosenFrsController::class, 'getFrsData'])->name('frs.data');
        Route::get('/frs/{id}', [DosenFrsController::class, 'show'])->name('dosen.frs.show');
        Route::put('/frs/persetujuan/{id}', [DosenFrsController::class, 'updateStatus'])->name('dosen.frs.update');

        // Route Nilai
        Route::get('/api/jadwal-kuliah', function () {
          return new DosenJadwalKuliahCollection(Jadwal::query());
        })->name('dosen.api.nilai');
      }
    );
  }
);

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
