<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SpaceFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company() . ' Auditorio';
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => 'Auditorio',
            'capacity' => fake()->numberBetween(50, 500),
            'description' => fake()->paragraph(),
            'price_per_hour' => fake()->randomElement([50000, 80000, 120000, null]),
            'is_active' => true,
        ];
    }
}