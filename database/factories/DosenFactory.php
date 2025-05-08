<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            //
            'nip' => fake()->unique()->numerify('##########'),
            'kode_jurusan' => fake()->randomElement([1, 2, 3]),
            'nama_dosen' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'telepon' => fake()->randomDigit(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
            'tanggal_lahir' => fake()->date(),
            'jabatan' => fake()->name(),
            'golongan_akhir' => fake()->name(),
            'is_wali' => fake()->boolean()
        ];
    }
}
