<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramStudi>
 */
class ProgramStudiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            ['kode' => 'IT', 'nama' => 'Teknik Informatika'],
            ['kode' => 'ELKA', 'nama' => 'Teknik Elektronika'],
            ['kode' => 'ELIN', 'nama' => 'Teknik Listrik'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['kode' => 'TK', 'nama' => 'Teknik Komputer'],
            ['kode' => 'TRM', 'nama' => 'Teknik Rekayasa Multimedia'],
            ['kode' => 'DS', 'nama' => 'Data Sains Terapan'],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika'],
            ['kode' => 'TTE', 'nama' => 'Teknik Telekomunikasi'],
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak'],
        ];

        static $counter = 0;
        $department = $departments[$counter % count($departments)];
        $counter++;

        return [
            'kode' => $department['kode'],
            'nama' => $department['nama'],
        ];
    }
}
