<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SpoonacularController extends Controller
{
    public function findByNutrients($protein)
    {
        $apiKey = env('SPOONACULAR_API_KEY'); 
        $response = Http::get('https://api.spoonacular.com/recipes/findByNutrients', [
            'minProtein' => $protein,
            'apiKey' => $apiKey, 
        ]);

        dd($response->json());
    }

    public function findByIngredients(Request $request)
    {
         // Sanitize ingredient input
        $ingredient = trim(strip_tags($request->input('ingredient')));

        if (empty($ingredient)) {
            return response()->json(['error' => 'Ingredient is required'], 400);
        }

        $apiKey = env('SPOONACULAR_API_KEY');

        // First API call to get recipes based on ingredient
        $response = Http::get("https://api.spoonacular.com/recipes/findByIngredients", [
            'ingredients' => $ingredient,
            'number' => 25,
            'apiKey' => $apiKey
        ]);

        if ($response->failed()) {
            Log::error('Spoonacular API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json([
                'error' => 'Failed to fetch recipes from Spoonacular',
                'api_response' => $response->body()
            ], 500);
        }

        $recipes = $response->json();

        if (!is_array($recipes) || empty($recipes)) {
            return response()->json(['error' => 'No recipes found or invalid response from API'], 404);
        }

        // Extract recipe IDs correctly (plain array, not JSON response)
        $recipeIds = collect($recipes)->pluck('id')->filter()->values();

        // return response()->json(['recipe_ids' => $recipeIds]);
            
        $detailedRecipes = [];

        // Loop through each recipe and fetch details
        foreach ($recipes as $recipe) {
            $recipeId = $recipe['id'] ?? null;
            if (empty($recipeId)) {
                continue;
            }
            // Fetch detailed recipe information
            $detailsResponse = Http::get("https://api.spoonacular.com/recipes/{$recipeId}/information", [
                'includeNutrition' => 'true',
                'apiKey' => $apiKey
            ]);
            $recipeDetails = $detailsResponse->json();

            if (empty($recipeDetails) || !is_array($recipeDetails)) {
                continue;
            }

            // Extract necessary details
            $ingredients = collect($recipeDetails['extendedIngredients'] ?? [])->pluck('name');
            $amounts = collect($recipeDetails['extendedIngredients'] ?? [])->pluck('amount');
            $units = collect($recipeDetails['extendedIngredients'] ?? [])->pluck('unit');
            $protein = $recipeDetails['nutrition']['caloricBreakdown']['percentProtein'] ?? 0;
            $fats = $recipeDetails['nutrition']['caloricBreakdown']['percentFat'] ?? 0;
            $carbs = $recipeDetails['nutrition']['caloricBreakdown']['percentCarbs'] ?? 0;

            $detailedRecipes[] = [
                'recipe_id' => $recipeId,
                'recipe_image' => $recipeDetails['image'] ?? 'No image available',
                'Time to be prepared' => $recipeDetails['readyInMinutes'] ?? '',
                'Title' => $recipeDetails['title'] ?? 'Unknown title',
                'NO. of people' => $recipeDetails['servings'] ?? 'N/A',
                'likes' => $recipeDetails['aggregateLikes'] ?? 0,
                'Ingredients' => $ingredients,
                'amount' => $amounts,
                'units' => $units,
                'nutrition' => $recipeDetails['nutrition']['nutrients'] ?? [],
                'total Protein' => "{$protein}%",
                'total Fats' => "{$fats}%",
                'total Carbs' => "{$carbs}%",
                'Weight/person' => ($recipeDetails['weightPerServing']['amount'] ?? 'Unknown') . ' ' . ($recipeDetails['weightPerServing']['unit'] ?? 'g'),
                'How to prepare it' => $recipeDetails['instructions'] ?? 'No instructions available',
            ];
        }

        return response()->json($detailedRecipes);
    }

}