<?php

// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Recipe;
use App\Models\Plan;
use App\Models\Question;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create recipes for the test user
        $recipes = Recipe::factory(5)->create([
            'user_id' => $testUser->id,
        ]);

        // Create plans for some recipes
        foreach ($recipes->take(3) as $recipe) {
            Plan::factory()->create([
                'user_id' => $testUser->id,
                'recipe_id' => $recipe->id,
            ]);
        }

        // Create questions for recipes
        foreach ($recipes as $recipe) {
            Question::factory(rand(1, 3))->create([
                'recipe_id' => $recipe->id,
            ]);
        }

        // Create additional random users with their data
        User::factory(10)
            ->has(Recipe::factory(3)->has(Question::factory(2)))
            ->has(Plan::factory(2))
            ->create();
    }
}
