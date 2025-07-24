<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->numerify('##########'), // 10 digits
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address(),
            'hobbies' => $this->faker->randomElements(['Reading', 'Gaming', 'Sports', 'Cooking', 'Traveling'], $this->faker->numberBetween(0, 3)),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
