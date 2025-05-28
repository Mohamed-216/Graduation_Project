<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MealPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $timeFrame = $request->input('timeFrame', 'week');
        $targetCalories = $request->input('targetCalories', 2000);
        $diet = $request->input('diet', '') ?: 'balanced';
        $exclude = $request->input('exclude', '') ?: 'none';
        $mealType = $request->input('mealType', ''); // Can be 'breakfast', 'lunch', 'dinner'

        // Fetch meal plan
        $apiKey = config('services.spoonacular.api_key', env('SPOONACULAR_API_KEY'));
        if (empty($apiKey)) {
            return response()->json(['error' => 'API key not set'], 500);
        }
        $queryParams = [
            'apiKey' => $apiKey,
            'timeFrame' => $timeFrame,
            'targetCalories' => $targetCalories,
            'diet' => $diet,
            'exclude' => $exclude,
        ];

        $response = Http::get("https://api.spoonacular.com/mealplanner/generate", $queryParams);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to fetch meal plan',
                'details' => $response->json()
            ], $response->status());
        }

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch meal plan'], 500);
        }

        $mealPlan = $response->json();

        if ($timeFrame === 'week') {
            if (!isset($mealPlan['week']) || !is_array($mealPlan['week'])) {
                return response()->json(['error' => 'Invalid API response'], 500);
            }

            $result = ['week' => []];

            foreach ($mealPlan['week'] as $day => $data) {
                if (!isset($data['meals']) || !isset($data['nutrients'])) {
                    continue;
                }

                // Filter by meal type (check if 'type' key exists)
                $meals = collect($data['meals'])->filter(function($meal) use ($mealType) {
                    return empty($mealType) || (isset($meal['type']) && $meal['type'] === $mealType);
                })->toArray();
                $mealIds = collect($meals)->pluck('id')->toArray();
                $recipes = $this->fetchDetailedRecipes($mealIds);

                $result['week'][$day] = [
                    'meals' => $recipes,
                    'nutrients' => $data['nutrients'],
                ];
            }

            return response()->json($result);
        } else {
            if (!isset($mealPlan['meals']) || !isset($mealPlan['nutrients'])) {
                return response()->json(['error' => 'Invalid API response'], 500);
            }
            // Filter by meal type (check if 'type' key exists)
            $meals = collect($mealPlan['meals'])->filter(function($meal) use ($mealType) {
                return empty($mealType) || (isset($meal['type']) && $meal['type'] === $mealType);
            })->toArray();
            $mealIds = collect($meals)->pluck('id')->toArray();
            $recipes = $this->fetchDetailedRecipes($mealIds);

            $result = [
                'meals' => $recipes,
                'nutrients' => $mealPlan['nutrients'],
            ];

            return response()->json($result);
        }
    }

    /**
     * Fetch detailed meal information including nutrition, instructions, and ingredients.
     * Replace the image with a high quality image from Pexels API.
     */
    private function fetchDetailedRecipes(array $mealIds)
    {
        return collect($mealIds)->map(function ($mealId) {
            // Get main recipe info
            $recipeResponse = Http::get("https://api.spoonacular.com/recipes/{$mealId}/information", [
                'apiKey' => env('SPOONACULAR_API_KEY')
            ]);

            if (!$recipeResponse->successful()) {
                return null;
            }

            $recipe = $recipeResponse->json();

            // Get nutrition info from nutritionWidget endpoint
            $nutritionResponse = Http::get("https://api.spoonacular.com/recipes/{$mealId}/nutritionWidget.json", [
                'apiKey' => env('SPOONACULAR_API_KEY')
            ]);

            $nutrition = [
                'calories' => 'N/A',
                'protein' => 'N/A',
                'fat' => 'N/A',
                'carbs' => 'N/A',
            ];

            if ($nutritionResponse->successful()) {
                $nutritionData = $nutritionResponse->json();
                $nutrition = [
                    'calories' => $nutritionData['calories'] ?? 'N/A',
                    'protein' => $nutritionData['protein'] ?? 'N/A',
                    'fat' => $nutritionData['fat'] ?? 'N/A',
                    'carbs' => $nutritionData['carbs'] ?? 'N/A',
                ];
            }

            // Replace image with Pexels high quality image
            $pexelsApiKey = config('services.pexels.api_key', env('PEXELS_API_KEY'));
            $pexelsImage = null;
            if (!empty($pexelsApiKey)) {
                $query = $recipe['title'] ?? '';
                $pexelsResponse = Http::withHeaders([
                    'Authorization' => $pexelsApiKey,
                ])->get('https://api.pexels.com/v1/search', [
                    'query' => $query,
                    'per_page' => 1,
                ]);
                if ($pexelsResponse->successful() && isset($pexelsResponse['photos'][0]['src']['large2x'])) {
                    $pexelsImage = $pexelsResponse['photos'][0]['src']['large2x'];
                }
            }

            return [
                'id' => $recipe['id'] ?? null,
                'title' => $recipe['title'] ?? 'Unknown Title',
                'image' => $pexelsImage ?? ($recipe['image'] ?? null),
                'readyInMinutes' => $recipe['readyInMinutes'] ?? 'N/A',
                'servings' => $recipe['servings'] ?? 'N/A',
                'sourceUrl' => $recipe['sourceUrl'] ?? null,
                'nutrition' => $nutrition,
                'instructions' => $recipe['instructions'] ?? 'No instructions provided',
                'ingredients' => isset($recipe['extendedIngredients']) && is_array($recipe['extendedIngredients'])
                    ? collect($recipe['extendedIngredients'])->map(fn($ing) => $ing['original'])->toArray()
                    : [],
            ];
        })->filter()->values();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}