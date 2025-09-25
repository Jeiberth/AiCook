<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PlanController extends Controller
{

    public function createPlan(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'date' => 'required|date',
                'recipe_id' => 'required|exists:recipes,id',
            ]);

            $plan = Plan::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'date' => $validated['date'],
                'recipe_id' => $validated['recipe_id'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $plan->load('recipe')
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Failed to create plan']
            ], 500);
        }
    }

    public function deletePlan(Request $request, $id)
    {
        try {
            $user = $request->user();
            $plan = Plan::where('user_id', $user->id)->findOrFail($id);

            $plan->delete();

            return response()->json([
                'success' => true,
                'data' => ['message' => 'Plan deleted successfully']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Plan not found or unauthorized']
            ], 404);
        }
    }

    public function updatePlan(Request $request, $id)
    {
        try {
            $user = $request->user();
            $plan = Plan::where('user_id', $user->id)->findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'date' => 'sometimes|date',
                'recipe_id' => 'sometimes|exists:recipes,id',
            ]);

            $plan->update($validated);

            return response()->json([
                'success' => true,
                'data' => $plan->fresh()->load('recipe')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'data' => ['errors' => $e->errors()]
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => ['message' => 'Plan not found or unauthorized']
            ], 404);
        }
    }
}
