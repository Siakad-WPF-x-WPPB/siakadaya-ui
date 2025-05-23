<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prodi_id' => ProgramStudi::inRandomOrder()->first()->id,
            'dosen_id' => Dosen::inRandomOrder()->first()->id,
            'pararel' => fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
        ];
    }
}
