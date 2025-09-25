<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $questions = [
            'Can I substitute chicken with tofu?',
            'What can I use instead of heavy cream?',
            'How do I make this recipe vegan?',
            'Can I prepare this ahead of time?',
            'What side dishes go well with this?',
            'How do I store leftovers?',
            'Can I freeze this recipe?',
            'What cooking temperature should I use?',
        ];

        $answers = [
            'Yes, you can substitute chicken with firm tofu. Press the tofu first and marinate it for better flavor.',
            'You can use coconut milk, cashew cream, or milk with butter as substitutes for heavy cream.',
            'To make this vegan, replace dairy with plant-based alternatives and use vegetable protein sources.',
            'Yes, you can prepare most components ahead of time and assemble before serving.',
            'This pairs well with a fresh salad, roasted vegetables, or crusty bread.',
            'Store leftovers in the refrigerator for up to 3-4 days in airtight containers.',
            'Yes, this recipe freezes well for up to 3 months. Thaw overnight before reheating.',
            'Cook at medium-high heat, around 375°F (190°C) for best results.',
        ];

        $questionIndex = fake()->numberBetween(0, count($questions) - 1);

        return [
            'question' => $questions[$questionIndex],
            'answer' => $answers[$questionIndex],
            'recipe_id' => Recipe::factory(),
        ];
    }
}
