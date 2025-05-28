<?php

use App\Http\Controllers\SpoonacularController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\MealPlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/recipes', [RecipeController::class, 'index']);
Route::post('/register', [AuthController::class, 'handleRegister']);
Route::post('/mealplan', [MealPlanController::class, 'index']);
Route::controller(SpoonacularController::class)->group(function () {
    Route::get('/findByNutrients/{protein}', 'findByNutrients')->where('protein', '[0-9]+');
    Route::get('/show/{recipeId}', 'show')->where('recipeId', '[0-9]+');
    Route::post('/search', 'findByIngredients');
});