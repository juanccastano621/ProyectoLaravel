<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Space;
use App\Models\Availability;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);

        $auditorios = [
            [
                'name' => 'Auditorio Magno',
                'capacity' => 300,
                'price' => 120000
            ],
            [
                'name' => 'Auditorio Empresarial',
                'capacity' => 180,
                'price' => 90000
            ],
            [
                'name' => 'Auditorio Académico',
                'capacity' => 120,
                'price' => 70000
            ],
            [
                'name' => 'Auditorio Ejecutivo',
                'capacity' => 80,
                'price' => 60000
            ],
        ];

        foreach ($auditorios as $item) {

            $space = Space::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'type' => 'Auditorio',
                'description' => 'Auditorio moderno equipado para eventos, conferencias y reuniones institucionales.',
                'capacity' => $item['capacity'],
                'price_per_hour' => $item['price'],
                'is_active' => true,
            ]);

            for ($day = 1; $day <= 7; $day++) {

                Availability::create([
                    'space_id' => $space->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '18:00:00',
                ]);
            }
        }

        Reservation::factory(20)->create();
    }
}