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
      // Create base data first
      ProgramStudi::factory(5)->create();
      Ruangan::factory(10)->create();
      Matakuliah::factory(15)->create();

      // Create inactive tahun ajar first
      TahunAjar::factory(9)->create(['status' => 'Tidak Aktif']);

      // Create one active tahun ajar with proper FRS dates
      $activeTahunAjar = TahunAjar::factory()->create([
          'status' => 'Aktif',
          'semester' => 'Genap',
          'tahun_mulai' => 2024,
          'tahun_akhir' => 2025,
          'mulai_frs' => now()->subDays(5),
          'selesai_frs' => now()->addDays(10),
          'mulai_edit_frs' => now()->addDays(11),
          'selesai_edit_frs' => now()->addDays(20),
          'mulai_drop_frs' => now()->addDays(21),
          'selesai_drop_frs' => now()->addDays(30),
      ]);

      // Create dosen
      Dosen::factory(5)->create();
      $testDosen = Dosen::factory()->create([
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
          'is_wali' => true
      ]);

      // Create kelas
      Kelas::factory(5)->create();
      $testKelas = Kelas::inRandomOrder()->first();

      // Create mahasiswa
      Mahasiswa::factory(5)->create();
      $testMahasiswa = Mahasiswa::factory()->create([
          'nrp' => '1234567890',
          'nama' => 'Budi Santoso',
          'email' => 'budi@gmail.com',
          'password' => bcrypt('password'),
          'telepon' => '081234567890',
          'tanggal_lahir' => '2000-01-01',
          'jenis_kelamin' => 'L',
          'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
          'kelas_id' => $testKelas->id, // Use the same kelas for testing
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

      // Create jadwal with active tahun ajar
      $testJadwal = Jadwal::factory()->create([
          'kelas_id' => $testKelas->id, // Same kelas as test student
          'dosen_id' => $testDosen->id,
          'mk_id' => Matakuliah::inRandomOrder()->first()->id,
          'ruangan_id' => Ruangan::inRandomOrder()->first()->id,
          'tahun_ajar_id' => $activeTahunAjar->id, // Use active tahun ajar
          'hari' => 'Senin',
          'jam_mulai' => '08:00:00',
          'jam_selesai' => '10:00:00',
      ]);

      // Create more jadwal for the active tahun ajar
      Jadwal::factory(10)->create([
          'tahun_ajar_id' => $activeTahunAjar->id
      ]);

      // Create admin
      Admin::create([
          'id' => (string) Str::uuid(),
          'nama' => 'Admin',
          'email' => 'raihan@gmail.com',
          'password' => bcrypt('password'),
      ]);

      // Create FRS with proper tahun_ajar_id
      $frs = Frs::create([
          'mahasiswa_id' => $testMahasiswa->id,
          'tahun_ajar_id' => $activeTahunAjar->id, // Use active tahun ajar
          'tanggal_pengisian' => now(),
      ]);

      // Create FRS Detail
      $frsDetail = FrsDetail::create([
          'frs_id' => $frs->id,
          'jadwal_id' => $testJadwal->id,
          'status' => 'pending',
          'tanggal_persetujuan' => now(),
      ]);

      // Create Nilai
      Nilai::factory()->create([
          'frs_detail_id' => $frsDetail->id,
          'status' => 'lulus',
          'nilai_huruf' => 'A',
          'nilai_angka' => 90,
      ]);
  }
}
