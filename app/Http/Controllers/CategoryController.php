<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    public function index(Request $request, $category)
{
    // Map categories to Spoonacular nutrient filters
    $nutrientFilters = match ($category) {
        'haircare' => ['minProtein' => 20, 'minIron' => 10, 'minZinc' => 5],
        'skincare' => ['minVitaminC' => 10, 'minVitaminE' => 5, 'minVitaminA' => 1000],
        'immunity-boost' => ['minVitaminC' => 15, 'minZinc' => 10, 'minVitaminD' => 5],
        'heart-health' => ['minOmega3' => 5, 'minFiber' => 5, 'maxSaturatedFat' => 10],
        'muscle-gain' => ['minProtein' => 30, 'minFat' => 10, 'minCarbs' => 50],
        default => [],
    };

    // Fetch filtered meals from Spoonacular
    $response = Http::get("https://api.spoonacular.com/recipes/complexSearch", array_merge([
        'apiKey' => env('SPOONACULAR_API_KEY'),
        'number' => 3,
    ], $nutrientFilters));

    return response()->json($response->json());
}
}
