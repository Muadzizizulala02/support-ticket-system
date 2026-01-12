<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             // Generates a random sentence like: "This is a random ticket title."
            'title' => $this->faker->sentence,
            // Generates a random paragraph
            'description' => $this->faker->paragraph,
            'user_id' => User::factory(), // auto-create user if not provided
            'status' => 'open',
        ];
    }
}
