<?php
namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class RecipeController extends Controller
{
    public function generateRecipes(Request $request)
    {
        try {
            $user = $request->user();

            // Check if user has recipe tokens
            if ($user->recipe_tokens <= 0) {
                return response()->json([
                    'success' => false,
                    'data' => ['message' => 'Insufficient recipe tokens']
                ], 404);
            }

            $validated = $request->validate([
                'allow_suggestions_with_missing_ingredients' => 'nullable|boolean',
                'portion_size' => 'nullable|integer',
                'type' => 'nullable|string',
                'cooking_time' => 'nullable|integer',
                'extra_instruction' => 'nullable|string',
            ]);

            // Prepare OpenAI API request
            $userContent = [
                'weight' => $user->weight,
                'height' => $user->height,
                'age' => $user->age,
                'goal' => $user->goal,
                'ingredients' => $user->ingredients ?? [],
                'dietaryPreferences' => $user->dietary_preferences ?? [],
                'allergies' => $user->allergies ?? [],
                'kitchenEquipment' => $user->kitchen_equipment ?? [],
            ];

            // Add optional parameters if provided
            if (isset($validated['cooking_time'])) {
                $userContent['cookingTime'] = $validated['cooking_time'];
            }
            if (isset($validated['type'])) {
                $userContent['type'] = $validated['type'];
            }
            if (isset($validated['portion_size'])) {
                $userContent['portionSize'] = $validated['portion_size'];
            }
            if (isset($validated['allow_suggestions_with_missing_ingredients'])) {
                $userContent['allowSuggestionsWithMissingIngredients'] = $validated['allow_suggestions_with_missing_ingredients'];
            }
            if (isset($validated['extra_instruction'])) {
                $userContent['extraInstruction'] = $validated['extra_instruction'];
            }

            // $response = Http::withHeaders([
            //     'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            //     'Content-Type' => 'application/json',
            // ])->post('https://api.openai.com/v1/chat/completions', [
            //     'model' => 'gpt-4o-2024-08-06',
            //     'messages' => [
            //         [
            //             'role' => 'system',
            //             'content' => "You are a professional chef AI who provides recipe suggestions. And respond to this request in {$user->language}"
            //         ],
            //         [
            //             'role' => 'user',
            //             'content' => 'Please generate 5 recipes based on the user model and optional parameters. Include the extra user instruction if provided. Some parameters might be missing, so handle flexibly.'
            //         ],
            //         [
            //             'role' => 'user',
            //             'content' => json_encode($userContent)
            //         ]
            //     ],
            //     'response_format' => [
            //         'type' => 'json_schema',
            //         'json_schema' => [
            //             'type' => 'array',
            //             'minItems' => 1,
            //             'maxItems' => 5,
            //             'items' => [
            //                 'type' => 'object',
            //                 'properties' => [
            //                     'name' => ['type' => 'string', 'description' => 'Short clear identifier of the recipe'],
            //                     'description' => ['type' => 'string', 'maxLength' => 100, 'description' => 'Brief summary under 100 characters'],
            //                     'time' => ['type' => 'integer', 'description' => 'Total cooking time in minutes'],
            //                     'servings' => ['type' => 'integer', 'description' => 'Number of servings'],
            //                     'ingredients' => [
            //                         'type' => 'array',
            //                         'items' => [
            //                             'type' => 'object',
            //                             'properties' => [
            //                                 'ing' => ['type' => 'string', 'description' => 'Ingredient description in human-readable units'],
            //                                 'have' => ['type' => 'boolean', 'description' => 'Whether the user has this ingredient']
            //                             ],
            //                             'required' => ['ing', 'have']
            //                         ]
            //                     ],
            //                     'instructions' => [
            //                         'type' => 'array',
            //                         'items' => ['type' => 'string', 'description' => 'Step-by-step instructions']
            //                     ]
            //                 ],
            //                 'required' => ['name', 'description', 'time', 'servings', 'ingredients', 'instructions']
            //             ],
            //             'additionalItems' => false
            //         ]
            //     ]
            // ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-2024-08-06',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are a professional chef AI who provides recipe suggestions. And respond to this request in {$user->language}"
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Please generate 5 recipes based on the user model and optional parameters. Include the extra user instruction if provided. Some parameters might be missing, so handle flexibly.'
                    ],
                    [
                        'role' => 'user',
                        'content' => json_encode($userContent)
                    ]
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'recipe_list',
                        'schema' => [
                            'type' => 'object', // ✅ root must be object
                            'properties' => [
                                'recipes' => [
                                    'type' => 'array',
                                    'minItems' => 1,
                                    'maxItems' => 5,
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'name' => ['type' => 'string', 'description' => 'Short clear identifier of the recipe'],
                                            'description' => ['type' => 'string', 'maxLength' => 100, 'description' => 'Brief summary under 100 characters'],
                                            'time' => ['type' => 'integer', 'description' => 'Total cooking time in minutes'],
                                            'servings' => ['type' => 'integer', 'description' => 'Number of servings'],
                                            'ingredients' => [
                                                'type' => 'array',
                                                'items' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'ing' => ['type' => 'string', 'description' => 'Ingredient description in human-readable units'],
                                                        'have' => ['type' => 'boolean', 'description' => 'Whether the user has this ingredient']
                                                    ],
                                                    'required' => ['ing', 'have']
                                                ]
                                            ],
                                            'instructions' => [
                                                'type' => 'array',
                                                'items' => ['type' => 'string', 'description' => 'Step-by-step instructions']
                                            ]
                                        ],
                                        'required' => ['name', 'description', 'time', 'servings', 'ingredients', 'instructions']
                                    ]
                                ]
                            ],
                            'required' => ['recipes']
                        ]
                    ]
                ]
            ]);



            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'data' => $response->json()
                ], 500);
            }

            // Get the raw OpenAI response
            $data = $response->json();

            // Extract the JSON string
            $content = $data['choices'][0]['message']['content'] ?? '{}';

            // Decode into PHP array
            $parsed = json_decode($content, true);

            // Grab the "recipes"
            $recipes = $parsed['recipes'] ?? [];

            // Decrease recipe tokens
            $user->decrement('recipe_tokens');

            return response()->json([
                'success' => true,
                'data' => $recipes
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Failed to generate recipes: ' . $e->getMessage(), "zzz" =>  $response->json()]
            ], 500);
        }
    }

    public function createRecipe(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'time' => 'required|integer',
                'servings' => 'required|integer',
                'ingredients' => 'required|array',
                'instructions' => 'required|array',
                'plan_name' => 'nullable|string|max:255',
                'plan_date' => 'nullable|date',
            ]);

            $recipe = Recipe::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'time' => $validated['time'],
                'servings' => $validated['servings'],
                'ingredients' => $validated['ingredients'],
                'instructions' => $validated['instructions'],
            ]);

            $plan = null;
            if (isset($validated['plan_name']) && isset($validated['plan_date'])) {
                $plan = Plan::create([
                    'name' => $validated['plan_name'],
                    'date' => $validated['plan_date'],
                    'recipe_id' => $recipe->id,
                    'user_id' => $user->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'recipe' => $recipe,
                    'plan' => $plan
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Failed to create recipe']
            ], 500);
        }
    }

    public function deleteRecipe(Request $request, $id)
    {
        try {
            $user = $request->user();
            $recipe = Recipe::where('user_id', $user->id)->findOrFail($id);

            $recipe->delete();

            return response()->json([
                'success' => true,
                'data' => ['message' => 'Recipe deleted successfully']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Recipe not found or unauthorized']
            ], 404);
        }
    }
}
