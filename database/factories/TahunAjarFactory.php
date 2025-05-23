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

        return [
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
            'tahun' => $startYear . '/' . $endYear,
        ];
    }
}
