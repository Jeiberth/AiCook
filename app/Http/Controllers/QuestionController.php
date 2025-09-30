<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    public function askQuestion(Request $request)
    {
        try {
            $user = $request->user();

            // Check if user has question tokens
            if ($user->question_tokens <= 0) {
                return response()->json([
                    'success' => false,
                    'data' => ['message' => 'Insufficient question tokens']
                ], 404);
            }

            $validated = $request->validate([
                'recipe_id' => 'required|exists:recipes,id',
                'question' => 'required|string',
            ]);

            $recipe = Recipe::findOrFail($validated['recipe_id']);

            // Prepare recipe data for OpenAI
            $recipeData = [
                'name' => $recipe->name,
                'description' => $recipe->description,
                'time' => $recipe->time,
                'servings' => $recipe->servings,
                'ingredients' => $recipe->ingredients,
                'instructions' => $recipe->instructions,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-2024-08-06',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are a helpful culinary assistant. And respond to this request in {$user->language}"
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here is a recipe:\n\n"
                                    . json_encode($recipeData, JSON_PRETTY_PRINT)
                                    . "\n\nBased on this recipe, please answer this question: \"{$validated['question']}\""
                    ]
                ]
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'data' => $response->json()
                ], 500);
            }

            // Safely extract the assistant's answer
            $data = $response->json();
            $answer = $data['choices'][0]['message']['content'] ?? null;

            // Save question and answer
            Question::create([
                'question' => $validated['question'],
                'answer' => $answer,
                'recipe_id' => $recipe->id,
            ]);

            // Decrease question tokens
            $user->decrement('question_tokens');

            return response()->json([
                'success' => true,
                'data' => ['answer' => $answer]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Failed to process question: ' . $e->getMessage()]
            ], 500);
        }
    }
}
