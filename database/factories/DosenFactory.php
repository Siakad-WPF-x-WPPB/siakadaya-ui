<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
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
            'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'telepon' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
            'tanggal_lahir' => fake()->date(),
            'jabatan' => fake()->jobTitle(),
            'golongan_akhir' => fake()->randomDigit(),
            'is_wali' => fake()->boolean()
        ];
    }
}
