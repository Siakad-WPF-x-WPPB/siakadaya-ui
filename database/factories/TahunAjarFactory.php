<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TahunAjar>
 */
class TahunAjarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $startYear = fake()->numberBetween(2000, date('Y'));
      $endYear = $startYear + 1;

        $startYear = fake()->numberBetween(date('Y') - 10, date('Y') + 2);
        $endYear = $startYear + 1;

        return [
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
            'tahun_mulai' => $startYear,
            'tahun_berakhir' => $endYear,
            'status' => fake()->randomElement(['Aktif', 'Tidak Aktif']),
        ];
    }
}
