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
        $programStudi = ProgramStudi::inRandomOrder()->first();

        $coursesByProgram = [
            'IT' => [
                'Pemrograman Web', 'Basis Data', 'Algoritma dan Struktur Data',
                'Jaringan Komputer', 'Sistem Operasi', 'Rekayasa Perangkat Lunak'
            ],
            'SI' => [
                'Sistem Informasi Manajemen', 'Analisis dan Perancangan Sistem',
                'E-Business', 'Enterprise Resource Planning', 'Business Intelligence'
            ],
            'TK' => [
                'Arsitektur Komputer', 'Sistem Digital', 'Mikroprosesor',
                'Embedded Systems', 'VLSI Design', 'Computer Hardware'
            ],
            'RPL' => [
                'Rekayasa Perangkat Lunak', 'Pengujian Perangkat Lunak',
                'Manajemen Proyek TI', 'Keamanan Sistem Informasi'
            ],
            'ELKA' => [
                'Dasar Teknik Elektronika', 'Sistem Kontrol', 'Rangkaian Listrik',
                'Komunikasi Data', 'Sistem Embedded'
            ],
            'ELIN' => [
                'Dasar Teknik Listrik', 'Sistem Tenaga Listrik', 'Pengukuran Listrik',
                'Instalasi Listrik', 'Sistem Distribusi Energi'
            ],
            'DS' => [
                'Statistika', 'Data Mining', 'Machine Learning',
                'Big Data', 'Analisis Data'
            ],
            'default' => [
                'Matematika', 'Fisika', 'Bahasa Inggris', 'Pancasila',
                'Kewarganegaraan', 'Entrepreneurship'
            ]
        ];

        $programCode = $programStudi->kode ?? 'default';
        $availableCourses = $coursesByProgram[$programCode] ?? $coursesByProgram['default'];

        return [
            'prodi_id' => $programStudi->id,

            'kode' => $programCode . fake()->unique()->numberBetween(101, 499),
            'nama' => fake()->randomElement($availableCourses),
            'semester' => fake()->numberBetween(1, 8),
            'sks' => fake()->numberBetween(1, 4),
            'tipe' => fake()->randomElement(['MW', 'MPP', 'MPI', 'MPK', 'MBKM']),
        ];
    }
}
