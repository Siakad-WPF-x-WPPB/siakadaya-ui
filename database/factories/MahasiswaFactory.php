<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_jurusan' => fake()->randomDigitNotNull(),
            'id_kelas' => fake()->randomDigitNotNull(),
            'nama_mahasiswa' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'telepon' => fake()->randomDigit(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(),
            'tanggal_lahir' => fake()->date(),
            'tanggal_masuk' => fake()->date(),
            'status' => fake()->randomElement(['Aktif', 'Cuti', 'Keluar']),
            'alamat' => fake()->address(),
        ];
    }
}
