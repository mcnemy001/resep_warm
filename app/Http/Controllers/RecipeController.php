<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use App\Models\FavoriteRecipe;

class RecipeController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.key');
    }

    // Metode untuk menampilkan 12 resep populer
    public function index()
    {
        try {
            $apiKey = env('SPOONACULAR_API_KEY');
            $response = Http::get('https://api.spoonacular.com/recipes/random', [
                'apiKey' => $apiKey,
                'number' => 12,
                'addRecipeInformation' => true
            ]);

            $recipes = $response->successful() ? $response->json()['recipes'] : [];
            
            return view('recipes.index', compact('recipes'));
        } catch (\Exception $e) {
            Log::error('Kesalahan mengambil resep: ' . $e->getMessage());
            return view('recipes.index', ['recipes' => []]);
        }
    }

    public function searchByName(Request $request)
{
    $query = $request->input('query');

    try {
        $apiKey = env('SPOONACULAR_API_KEY');
        $response = Http::get('https://api.spoonacular.com/recipes/complexSearch', [
            'apiKey' => $apiKey,
            'query' => $query,
            'number' => 12,
            'addRecipeInformation' => true
        ]);

        $recipes = $response->successful() ? $response->json()['results'] : [];
        
        return view('recipes.search', compact('recipes'));
    } catch (\Exception $e) {
        Log::error('Kesalahan mencari resep: ' . $e->getMessage());
        return view('recipes.search', ['recipes' => []]);
    }
}
    public function recommendByIngredients(Request $request)
    {
        $ingredients = $request->input('ingredients', '');
        
        if (empty($ingredients)) {
            return view('recipes.recommend', ['recipes' => []]);
        }
        
        try {
            $response = Http::get('https://api.spoonacular.com/recipes/findByIngredients', [
                'apiKey' => env('SPOONACULAR_API_KEY'),
                'ingredients' => $ingredients,
                'number' => 12,
                'ranking' => 1  // Maximize used ingredients
            ]);

            if ($response->successful()) {
                $recipes = $response->json();
                return view('recipes.recommend', compact('recipes'));
            } else {
                Log::error('Kesalahan API Spoonacular: ' . $response->body());
                return view('recipes.recommend', [
                    'recipes' => [],
                    'error' => 'Failed to find recipes based on ingredients. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Kesalahan rekomendasi resep: ' . $e->getMessage());
            return view('recipes.recommend', [
                'recipes' => [],
                'error' => 'Failed to fetch recommended recipes: ' . $e->getMessage()
            ]);
        }
    }

    public function getRecipeDetails($id)
    {
        try {
            // Pastikan Anda menggunakan Auth::user() dengan benar
            $user = Auth::user(); // Tambahkan ini jika belum ada

            $apiKey = env('SPOONACULAR_API_KEY');
            $response = Http::get("https://api.spoonacular.com/recipes/{$id}/information", [
                'apiKey' => $apiKey,
                'includeNutrition' => false
            ]);

            if ($response->successful()) {
                $recipe = $response->json();

                // Cek apakah resep sudah ada di favorit
                $favorite = FavoriteRecipe::where('user_id', $user->id)
                    ->where('recipe_id', $id)
                    ->first();

                return view('recipes.details', [
                    'recipe' => $recipe,
                    'favorite' => $favorite // Tambahkan variabel favorite
                ]);
            }

            return back()->with('error', 'Failed to fetch recipe details');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch recipe details: ' . $e->getMessage());
        }
    }

}