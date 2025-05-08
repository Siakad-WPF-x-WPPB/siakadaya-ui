<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_matakuliah' => fake()->unique()->numerify('#####'),
            'kode_jurusan' => fake()->randomDigitNotNull(),
            'nama_matakuliah' => fake()->name(),
            'sks' => fake()->name(),
            'semester' => fake()->name(),
            'tipe_matakuliah' => fake()->randomElement(['MPK', 'MPI', 'MW']),
        ];
    }
}
