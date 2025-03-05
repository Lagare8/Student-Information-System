<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        return [
            'student_id' => 'STU' . fake()->unique()->numberBetween(1000, 9999),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'course' => 'BS Information Technology',
            'year_level' => fake()->randomElement($yearLevels),
        ];
    }
} 
