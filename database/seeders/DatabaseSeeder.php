<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use App\Models\TahunAjar;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  
  public function run(): void
  {

    TahunAjar::factory(10)->create();
    ProgramStudi::factory(10)->create();
    Dosen::factory(10)->create();
    Kelas::factory(10)->create();
    Mahasiswa::factory(10)->create();
    Ruangan::factory(10)->create();
    Matakuliah::factory(10)->create();
    Jadwal::factory(10)->create();
    // User::factory(10)->create();

    // User::factory()->create([
    //   'name' => 'Test User',
    //   'email' => 'test@example.com',
    // ]);
    
  //   $dosen = Dosen::factory()->create([
  //     'nip' => '1234567890',
  //     'nama_dosen' => 'Dr. Andi Setiawan',
  //     'email' => 'andisetiawan@example.com',
  //     'telepon' => '081234567890',
  //     'tanggal_lahir' => '1980-05-15',
  //     'jenis_kelamin' => 'L',
  //     'kode_jurusan' => '1',
  //     'jabatan' => 'Dosen Tetap',
  //     'golongan_akhir' => 'III/a',
  //     'is_wali' => false
  // ]);

  // Kelas::factory()->create([
  //   'nama_kelas' => 'D3 IT B',
  //   'dosen_id' => $dosen->id  // <- ini penting
  // ]);
  }
}
