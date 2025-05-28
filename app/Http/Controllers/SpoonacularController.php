<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    // public function findByIngredients($ingredient, $recipeIndex = 0)
    public function findByIngredients(Request $request)
    {
        // Retrieve values from both JSON and regular form input
        $ingredient = $request->input('ingredient', '');
        $apiKey = env('SPOONACULAR_API_KEY');

        // First API call to get recipe ID based on ingredient
        $response = Http::get("https://api.spoonacular.com/recipes/findByIngredients", [
            'ingredients' => $ingredient,
            'apiKey' => $apiKey
        ]);
        $recipes = $response->json();

        if (empty($recipes)) {
            return response()->json(['error' => 'No recipes found'], 404);
        }

        return response()->json($recipes);
    }
    public function show($recipeId)
    {
        $apiKey = env('SPOONACULAR_API_KEY'); 
        $response = Http::get('https://api.spoonacular.com/recipes/' . $recipeId . '/information', [
            'includeNutrition' => 'true',
            'apiKey' => $apiKey
        ]);
        $recipeDetails = $response->json();

        if (!$response->ok() || empty($recipeDetails)) {
            return response()->json(['error' => 'Recipe details not found'], 404);
        }

        $ingredients = collect($recipeDetails['extendedIngredients'] ?? [])->pluck('name');
        $amount = collect($recipeDetails['extendedIngredients'] ?? [])->pluck('amount');
        $units = collect($recipeDetails['extendedIngredients'] ?? [])->pluck('unit');
        $protein = $recipeDetails['nutrition']['caloricBreakdown']['percentProtein'] ?? 0;
        $fats = $recipeDetails['nutrition']['caloricBreakdown']['percentFat'] ?? 0;
        $carbs = $recipeDetails['nutrition']['caloricBreakdown']['percentCarbs'] ?? 0;

        return response()->json([
            'recipe_id' => $recipeId,
            'recipe_image' => $recipeDetails['image'] ?? 'No image available',
            'Time to be prepared' => $recipeDetails['readyInMinutes'] ?? '',
            'Title' => $recipeDetails['title'] ?? 'Unknown title',
            'NO. of people' => $recipeDetails['servings'] ?? 'N/A',
            'likes' => $recipeDetails['aggregateLikes'] ?? 0,
            'Ingredients' => $ingredients,
            'amount' => $amount,
            'units' => $units,
            'nutrition' => $recipeDetails['nutrition']['nutrients'] ?? [],
            'total Protein' => $protein . '%',
            'total Fats' => $fats . '%',
            'total Carbs' => $carbs . '%',
            'Weight/person' => ($recipeDetails['weightPerServing']['amount'] ?? 'Unknown') . ' ' . ($recipeDetails['weightPerServing']['unit'] ?? 'g'),
            'How to prepare it' => $recipeDetails['instructions'] ?? 'No instructions available',
        ]);
    }
}


    // public function search(Request $request)
    // {
    //     $query = $request->input('query');
    //     $apiKey = env('SPOONACULAR_API_KEY');

    //     $response = Http::get("https://api.spoonacular.com/recipes/complexSearch", [
    //         'apiKey' => $apiKey,
    //         'query' => $query
    //     ]);

    //     return response()->json($response->json());
    // }