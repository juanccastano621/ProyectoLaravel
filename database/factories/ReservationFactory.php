<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $date = Carbon::now()
            ->addDays(fake()->numberBetween(-10, 20))
            ->setHour(fake()->numberBetween(8, 17))
            ->setMinute(0);

        return [
            'space_id' => Space::inRandomOrder()->first()?->id,
            'user_name' => fake()->name(),
            'user_email' => fake()->safeEmail(),
            'start_time' => $date,
            'end_time' => $date->copy()->addHour(),
            'status' => fake()->randomElement([
                'pendiente',
                'confirmada',
                'rechazada',
                'cancelada'
            ]),
            'notes' => fake()->optional()->sentence()
        ];
    }
}