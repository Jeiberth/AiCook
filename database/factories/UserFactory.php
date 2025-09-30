<?php

// database/factories/UserFactory.php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'weight' => fake()->randomFloat(2, 50, 120),
            'height' => fake()->randomFloat(2, 150, 200),
            'age' => fake()->numberBetween(18, 80),
            'goal' => fake()->randomElement(['weight loss', 'weight gain', 'muscle building', 'maintenance']),
            'ingredients' => [
                fake()->randomElement(['chicken', 'beef', 'fish', 'tofu']),
                fake()->randomElement(['rice', 'pasta', 'bread', 'quinoa']),
                fake()->randomElement(['tomatoes', 'onions', 'carrots', 'broccoli']),
            ],
            'dietary_preferences' => fake()->randomElements([
                'vegetarian', 'vegan', 'gluten-free', 'dairy-free', 'low-carb', 'keto'
            ], fake()->numberBetween(0, 3)),
            'allergies' => fake()->randomElements([
                'nuts', 'shellfish', 'eggs', 'dairy', 'soy', 'gluten'
            ], fake()->numberBetween(0, 2)),
            'kitchen_equipment' => fake()->randomElements([
                'oven', 'stovetop', 'microwave', 'blender', 'food processor', 'air fryer'
            ], fake()->numberBetween(2, 4)),
            'recipe_tokens' => fake()->numberBetween(5, 50),
            'question_tokens' => fake()->numberBetween(5, 50),
            'language' => fake()->randomElement(['en', 'es', 'fr', 'de', 'it']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
