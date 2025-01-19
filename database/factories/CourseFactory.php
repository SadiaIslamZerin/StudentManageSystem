<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 year');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');

        return [
            'name' => $this->faker->randomElement([
                'Introduction to Programming',
                'Web Development Bootcamp',
                'Data Science Fundamentals',
                'Machine Learning with Python',
                'Graphic Design Masterclass',
                'Digital Marketing Essentials',
                'Cybersecurity Basics',
                'Artificial Intelligence for Beginners',
                'Mobile App Development',
                'Blockchain Technology Overview'
            ]),
            'duration' => $this->faker->numberBetween(1, 12),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'class_start_hour' => $this->faker->time('H:i:s'),
            'class_end_hour' => $this->faker->time('H:i:s'),
            'classdays' => $this->faker->randomElement(['Mon, Tue, Wed', 'Thu, Fri, Sat', 'Sun, Mon, Wed', 'Fri, Sun, Tue, Wed']),
            'fees' => $this->faker->randomFloat(2, 5000, 20000),
        ];
    }
}
