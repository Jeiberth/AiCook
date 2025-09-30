<?php

// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function updateUser(Request $request)
    {
        try {
            $user = $request->user();

            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|string|min:8',
                'weight' => 'sometimes|nullable|numeric',
                'height' => 'sometimes|nullable|numeric',
                'age' => 'sometimes|nullable|integer',
                'goal' => 'sometimes|nullable|string',
                'ingredients' => 'sometimes|nullable|array',
                'dietary_preferences' => 'sometimes|nullable|array',
                'allergies' => 'sometimes|nullable|array',
                'kitchen_equipment' => 'sometimes|nullable|array',
                'recipe_tokens' => 'sometimes|nullable|integer',
                'question_tokens' => 'sometimes|nullable|integer',
                'language' => 'sometimes|nullable|string|max:10',
            ]);

            // Hash password if provided
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // Update only the fields that were provided
            $user->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $user->fresh()
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Update failed']
            ], 500);
        }
    }
}
