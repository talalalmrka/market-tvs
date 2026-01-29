<?php

namespace Database\Factories;

use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slide>
 */
class SlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'time_slot_id' => TimeSlot::factory(),
            'name' => $this->faker->sentence(3),
            'duration' => 5000,
            'transition' => $this->faker->randomElement(['fade', 'slide', 'zoom']),
            'order' => 0,
            'is_active' => true,
        ];
    }
}
