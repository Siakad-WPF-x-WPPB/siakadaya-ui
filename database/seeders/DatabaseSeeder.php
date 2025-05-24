<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Frs;
use App\Models\FrsDetail;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\Nilai;
use App\Models\ProgramStudi;
use App\Models\Ruangan;
use App\Models\TahunAjar;
use App\Models\User;
use Illuminate\Support\Str;
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
    Nilai::factory(10)->create();
    // User::factory(10)->create();

    // User::factory()->create([
    //   'name' => 'Test User',
    //   'email' => 'test@example.com',
    // ]);
    
    $dosen = Dosen::factory()->create([
      'nip' => '3123500056',
      'nama' => 'Muhammad Raihan',
      'email' => 'raihanm@gmail.com',
      'password' => bcrypt('password'),
      'telepon' => '081234567890',
      'tanggal_lahir' => '1980-05-15',
      'jenis_kelamin' => 'L',
      'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
      'jabatan' => 'Dosen Tetap',
      'golongan_akhir' => 'III/a',
      'is_wali' => false
  ]);

  Nilai::factory()->create([
      'mahasiswa_id' => Mahasiswa::inRandomOrder()->first()->id,
      'mk_id' => Matakuliah::inRandomOrder()->first()->id,
      'dosen_id' => $dosen->id, // <- ini penting
      'tahun_ajar_id' => TahunAjar::inRandomOrder()->first()->id,
      'status' => 'lulus',
      'nilai_huruf' => 'A',
      'nilai_angka' => 90,
  ]);

  Admin::create([
      'id' => (string) Str::uuid(),
      'nama' => 'Admin',
      'email' => 'raihan@gmail.com',
      'password' => bcrypt('password'),
  ]);

    // Frs::create([
    //   'mahasiswa_id' => Mahasiswa::inRandomOrder()->first()->id,
    //   'jadwal_id' => Jadwal::inRandomOrder()->first()->id,
    //   'tahun_ajar_id' => TahunAjar::inRandomOrder()->first()->id,
    //   'tanggal_pengisian' => now(),
    // ]);

    Mahasiswa::factory()->create([
      'nrp' => '1234567890',
      'nama' => 'Budi Santoso',
      'email' => 'budi@gmail.com',
      'password' => bcrypt('password'),
      'telepon' => '081234567890',
      'tanggal_lahir' => '2000-01-01',
      'jenis_kelamin' => 'L',
      'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
      'kelas_id' => Kelas::inRandomOrder()->first()->id,
      'agama' => 'Islam',
      'semester' => '1',
      'tanggal_masuk' => '2020-08-01',
      'status' => 'Aktif',
      'alamat_jalan' => 'Jl. Raya No. 1',
      'provinsi' => 'Jawa Barat',
      'kode_pos' => '12345',
      'negara' => 'Indonesia',
      'kelurahan' => 'Kelurahan 1',
      'kecamatan' => 'Kecamatan 1',
      'kota' => 'Kota 1'
  ]);

  $jadwal = Jadwal::factory()->create([
    'kelas_id' => Kelas::inRandomOrder()->first()->id,
    'dosen_id' => $dosen->id,
    'mk_id' => Matakuliah::inRandomOrder()->first()->id,
    'ruangan_id' => Ruangan::inRandomOrder()->first()->id,
    'hari' => 'Senin',
    'jam_mulai' => '08:00:00',
    'jam_selesai' => '10:00:00',
  ]);

    Frs::create([
      'mahasiswa_id' => Mahasiswa::inRandomOrder()->first()->id,
      'tahun_ajar_id' => TahunAjar::inRandomOrder()->first()->id,
      'tanggal_pengisian' => now(),
    ]);

    FrsDetail::create([
      'frs_id' => Frs::inRandomOrder()->first()->id,
      'jadwal_id' => $jadwal->id,
      'status' => 'pending',
      'tanggal_persetujuan' => now(),
    ]);

  // Kelas::factory()->create([
  //   'nama_kelas' => 'D3 IT B',
  //   'dosen_id' => $dosen->id  // <- ini penting
  // ]);
  }
}
