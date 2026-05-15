<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Space;
use App\Models\Availability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $spaces = [
            ['name' => 'Auditorio Magno', 'type' => 'Auditorio', 'capacity' => 150, 'price' => 50.00],
            ['name' => 'Laboratorio Químico', 'type' => 'Laboratorio', 'capacity' => 25, 'price' => 30.00],
            ['name' => 'Cancha de Microfútbol', 'type' => 'Deportivo', 'capacity' => 12, 'price' => 20.00],
        ];

        foreach ($spaces as $s) {
            $space = Space::create([
                'name' => $s['name'],
                'slug' => Str::slug($s['name']),
                'type' => $s['type'],
                'description' => 'Espacio profesional para uso compartido.',
                'capacity' => $s['capacity'],
                'price_per_hour' => $s['price'],
                'is_active' => true,
            ]);

            for ($i = 1; $i <= 5; $i++) {
                Availability::create([
                    'space_id' => $space->id,
                    'day_of_week' => $i,
                    'start_time' => '08:00:00',
                    'end_time' => '18:00:00',
                ]);
            }
        }
    }
}