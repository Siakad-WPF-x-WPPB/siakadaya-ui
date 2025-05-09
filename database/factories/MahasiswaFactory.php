<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nrp' => fake()->unique()->numerify('##########'),
      'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
      'kelas_id' => Kelas::inRandomOrder()->first()->id, // pastikan tabel kelas ada
      'nama' => fake()->name(),
      'jenis_kelamin' => fake()->randomElement(['L', 'P']),
      'telepon' => fake()->numerify('08##########'),
      'email' => fake()->unique()->safeEmail(),
      'password' => Hash::make('password'), // jangan gunakan fake()->password() langsung
      'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
      'semester' => fake()->numberBetween(1, 8),
      'tanggal_lahir' => fake()->date('Y-m-d', '-18 years'),
      'tanggal_masuk' => fake()->date('Y-m-d', '-4 years'),
      'status' => fake()->randomElement(['Aktif', 'Cuti', 'Keluar']),
      'alamat_jalan' => fake()->streetAddress(),
      'provinsi' => fake()->state(),
      'kode_pos' => fake()->postcode(),
      'negara' => 'Indonesia',
      'kelurahan' => fake()->citySuffix(),
      'kecamatan' => fake()->streetSuffix(),
      'kota' => fake()->city(),
    ];
  }
}
