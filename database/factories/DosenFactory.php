<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $academicPositions = [
        'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar',
        'Dosen', 'Dosen Luar Biasa', 'Profesor'
      ];

      $golonganOptions = [
          'III/a', 'III/b', 'III/c', 'III/d',
          'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'
      ];

        return [
            'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
            'nip' => fake()->unique()->numerify('##################'),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),

            'telepon' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),

            'tanggal_lahir' => fake()->dateTimeBetween('-65 years', '-25 years')->format('Y-m-d'),
            'jabatan' => fake()->randomElement($academicPositions),
            'golongan_akhir' => fake()->randomElement($golonganOptions),
            'is_wali' => fake()->boolean(30)
        ];
    }
}
