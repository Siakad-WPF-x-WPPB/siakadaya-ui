<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
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
            'kode' => fake()->unique()->numerify('#####'),
            'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
            'nama' => fake()->name(),
            'semester' => fake()->name(),
            'sks' => fake()->name(),
            'tipe_matakuliah' => fake()->randomElement(['MPK', 'MPI', 'MW']),
        ];
    }
}
