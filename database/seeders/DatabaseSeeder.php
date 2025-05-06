<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mahasiswa;
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
    Jurusan::factory(10)->create();
    Dosen::factory(10)->create();
    Kelas::factory(10)->create();
    Mahasiswa::factory(10)->create();
    Ruangan::factory(10)->create();
    // User::factory(10)->create();

    // User::factory()->create([
    //   'name' => 'Test User',
    //   'email' => 'test@example.com',
    // ]);
  }
}
