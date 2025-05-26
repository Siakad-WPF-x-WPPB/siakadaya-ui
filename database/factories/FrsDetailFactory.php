<?php

namespace Database\Factories;

use App\Models\Frs;
use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FrsDetail>
 */
class FrsDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'frs_id' => Frs::inRandomOrder()->first()?->id ?? Frs::factory(),
            'jadwal_id' => Jadwal::inRandomOrder()->first()?->id ?? Jadwal::factory(),
            'status' => $this->faker->randomElement(['disetujui', 'pending', 'ditolak']),
        ];
    }
}
