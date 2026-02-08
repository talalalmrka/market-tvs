<?php

namespace Database\Factories;

use App\Models\Screen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeSlot>
 */
class TimeSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('00:00', '23:59');

        // Ensure end time is 1-4 hours after start time
        $end = (clone $start)->modify('+' . rand(1, 4) . ' hours');


        return [
            'screen_id' => Screen::factory(),
            'name' => $this->faker->randomElement(['Breakfast', 'Lunch', 'Dinner', 'Evening Promo']),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'duration' => 5000,
            'is_active' => true,
        ];
    }
}
