<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'weight' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'age' => 'nullable|integer',
                'goal' => 'nullable|string',
                'ingredients' => 'nullable|array',
                'dietary_preferences' => 'nullable|array',
                'allergies' => 'nullable|array',
                'kitchen_equipment' => 'nullable|array',
                'language' => 'nullable|string|max:10',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'weight' => $validated['weight'] ?? null,
                'height' => $validated['height'] ?? null,
                'age' => $validated['age'] ?? null,
                'goal' => $validated['goal'] ?? null,
                'ingredients' => $validated['ingredients'] ?? [],
                'dietary_preferences' => $validated['dietary_preferences'] ?? [],
                'allergies' => $validated['allergies'] ?? [],
                'kitchen_equipment' => $validated['kitchen_equipment'] ?? [],
                'language' => $validated['language'] ?? 'en',
            ]);

            $token = $user->createToken('api')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'token' => $token
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
                'data' => ['message' => 'Registration failed']
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'data' => ['message' => 'Invalid credentials']
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('api')->plainTextToken;

            // Get user with all recipes and questions, and all plans with recipes
            $userData = User::with([
                'recipes.questions',
                'plans.recipe'
            ])->find($user->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $userData,
                    'token' => $token
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Login failed']
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'data' => ['message' => 'Logged out successfully']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Logout failed']
            ], 500);
        }
    }
}
