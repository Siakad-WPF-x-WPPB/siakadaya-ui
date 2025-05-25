<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Matakuliah;
use App\Models\Ruangan;
use App\Models\TahunAjar;
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
        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $timeSlots = [
            ['07:00:00', '08:40:00'],
            ['08:40:00', '10:20:00'],
            ['10:30:00', '12:10:00'],
            ['12:10:00', '13:50:00'],
            ['13:50:00', '15:30:00'],
            ['15:40:00', '17:20:00'],
            ['18:30:00', '20:10:00'],
        ];

        $selectedSlot = fake()->randomElement($timeSlots);

        return [
            'kelas_id' => Kelas::inRandomOrder()->first()->id,
            'dosen_id' => Dosen::inRandomOrder()->first()->id,
            'mk_id' => Matakuliah::inRandomOrder()->first()->id,
            'ruangan_id' => Ruangan::inRandomOrder()->first()->id,
            'tahun_ajar_id' => TahunAjar::inRandomOrder()->first()->id,

            'jam_mulai' => $selectedSlot[0],
            'jam_selesai' => $selectedSlot[1],
            'hari' => fake()->randomElement($daysOfWeek),
        ];
    }
}
