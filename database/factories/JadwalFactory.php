<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::inRandomOrder()->first()->id,
            'dosen_id' => Dosen::inRandomOrder()->first()->id,
            'mk_id' => Matakuliah::inRandomOrder()->first()->id,
            'ruangan_id' => Ruangan::inRandomOrder()->first()->id,
            'jam_mulai' => fake()->time(),
            'jam_selesai' => fake()->time(),
            'hari' => fake()->date(),
        ];
    }
}
