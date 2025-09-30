<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
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
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(10),
            'time' => fake()->numberBetween(15, 120),
            'servings' => fake()->numberBetween(1, 8),
            'ingredients' => [
                [
                    'ing' => '2 cups rice',
                    'have' => fake()->boolean()
                ],
                [
                    'ing' => '1 lb chicken breast',
                    'have' => fake()->boolean()
                ],
                [
                    'ing' => '1 onion, diced',
                    'have' => fake()->boolean()
                ],
                [
                    'ing' => '2 cloves garlic, minced',
                    'have' => fake()->boolean()
                ]
            ],
            'instructions' => [
                'Prepare all ingredients',
                'Heat oil in a large pan',
                'Cook protein until done',
                'Add vegetables and seasonings',
                'Simmer until tender',
                'Serve hot'
            ],
        ];
    }
}
