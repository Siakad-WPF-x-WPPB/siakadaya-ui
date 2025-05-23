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
    $indonesianProvinces = [
      'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur',
      'Sumatera Utara', 'Sumatera Barat', 'Bali', 'Kalimantan Timur'
    ];

    $indonesianCities = [
      'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang',
      'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi'
    ];

    return [
      'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
      'kelas_id' => Kelas::inRandomOrder()->first()->id,

      'nrp' => fake()->unique()->numerify('3123######'),
      'nama' => fake()->name(),
      'jenis_kelamin' => fake()->randomElement(['L', 'P']),

      'telepon' => fake()->numerify('08##########'),
      'email' => fake()->unique()->safeEmail(),
      'password' => Hash::make('password'),
      'agama' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
      'semester' => fake()->randomElement(['1', '2', '3', '4', '5', '6', '7', '8']),
      'tanggal_lahir' => fake()->date('Y-m-d', '-18 years'),
      'tanggal_masuk' => fake()->date('Y-m-d', '-4 years'),
      'status' => fake()->randomElement(['Aktif', 'Cuti', 'Keluar']),

      'alamat_jalan' => fake()->streetAddress(),
      'provinsi' => fake()->randomElement($indonesianProvinces),
      'kode_pos' => fake()->numberBetween(10000, 99999),
      'negara' => 'Indonesia',
      'kelurahan' => fake()->citySuffix(),
      'kecamatan' => fake()->streetSuffix(),
      'kota' => fake()->randomElement($indonesianCities),
    ];
  }
}
