<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruangan>
 */
class RuanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buildings = [
          'Gedung D3', 'Gedung D4', 'Gedung Pascasarjana', 'Gedung SAW'
        ];

        $rootName = [
          'Lab Jaringan Komputer',
          'Lab Rekayasa Perangkat Lunak',
          'Lab Sistem Digital',
          'Lab Sistem Operasi',
          'Lab Pemrograman Web',
          'Lab Basis Data',
          'Lab Data Mining',
          'Auditorium',
          'Mini Theater',
        ];

        $buildingCodes = ['A', 'B', 'C', 'D', 'TI', 'TE', 'LAB'];
        $buildingCode = fake()->randomElement($buildingCodes);
        $floor = fake()->numberBetween(1, 7);
        $roomNumber = fake()->numberBetween(1, 20);
        $roomCode = $buildingCode . $floor . str_pad($roomNumber, 2, '0', STR_PAD_LEFT);

        return [
            'kode' => $roomCode,
            'nama' => fake()->randomElement($rootName) . ' ' . $roomCode,
            'gedung' => fake()->randomElement($buildings),
        ];
    }
}
