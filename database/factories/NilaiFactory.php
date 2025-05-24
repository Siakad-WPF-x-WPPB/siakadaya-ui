<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\TahunAjar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nilai>
 */
class NilaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mahasiswa_id' => Mahasiswa::inRandomOrder()->first()->id,
            'mk_id' => Matakuliah::inRandomOrder()->first()->id,
            'dosen_id' => Dosen::inRandomOrder()->first()->id,
            'tahun_ajar_id' => TahunAjar::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['lulus', 'tidak lulus']),
            'nilai_huruf' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'nilai_angka' => $this->faker->numberBetween(0, 100),
        ];
    }
}
