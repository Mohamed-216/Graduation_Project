<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    public function index()
    {
        // Make API request to Spoonacular
        $response = Http::get("https://api.spoonacular.com/recipes/random", [
            'apiKey' => env('SPOONACULAR_API_KEY'),
            'number' => 1 // Fetch 10 random recipes
        ]);

        // Decode response JSON
        $recipes = $response->json();

        // Return formatted API response
        return response()->json($recipes);
    }
}
