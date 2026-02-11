<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'parent_id' => null,
            'name' => $name,
            'type' => 'category',
            'description' => $this->faker->sentence,
        ];
    }
    public function parent($id)
    {
        return $this->state(fn(array $attributes) => [
            'parent_id' => $id,
            'type' => 'category',
        ]);
    }
    public function tag(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'tag',
        ]);
    }
    public function name($name): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => $name,
        ]);
    }
}
