<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Models\Availability;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Space::query()->delete(); 
        
        $spaces = Space::factory(5)->create();

        foreach ($spaces as $space) {
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