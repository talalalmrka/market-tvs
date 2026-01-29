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
        $startHour = $this->faker->numberBetween(6, 18);
        $endHour = $startHour + $this->faker->numberBetween(1, 4);


        return [
            'screen_id' => Screen::factory(),
            'name' => $this->faker->randomElement(['Breakfast', 'Lunch', 'Dinner', 'Evening Promo']),
            'start_time' => sprintf('%02d:00:00', $startHour),
            'end_time' => sprintf('%02d:00:00', $endHour),
            'slide_duration' => 5000,
            'priority' => 1,
            'is_active' => true,
        ];
    }
}
