<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Screen>
 */
class ScreenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'uuid' => Str::uuid(),
            'name' => $this->faker->company . ' TV',
            'is_active' => true,
        ];
    }
    /**
     * Attach screen to specific user
     */
    public function user(int|User $user): static
    {
        return $this->state(function () use ($user) {
            return [
                'user_id' => $user instanceof User ? $user->id : $user,
            ];
        });
    }
}
