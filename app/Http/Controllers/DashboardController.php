<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\FavoriteRecipe;
use App\Models\MyRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil pesan selamat datang dari session
        $welcomeMessage = session('welcome', null);
        
        // Hitung total resep favorit
        $totalFavorites = FavoriteRecipe::where('user_id', $user->id)->count();
        
        // Hitung total resep pribadi
        $totalMyRecipes = MyRecipe::where('user_id', $user->id)->count();
        
        // Ambil resep favorit terbaru
        $recentFavorites = FavoriteRecipe::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Ambil resep rekomendasi
        try {
            $apiKey = env('SPOONACULAR_API_KEY');
            $response = Http::get('https://api.spoonacular.com/recipes/random', [
                'apiKey' => $apiKey,
                'number' => 4,
                'addRecipeInformation' => true
            ]);

            $recommendedRecipes = $response->successful() ? $response->json()['recipes'] : [];
        } catch (\Exception $e) {
            $recommendedRecipes = [];
        }

        return view('dashboard', compact(
            'user', 
            'welcomeMessage', 
            'totalFavorites', 
            'totalMyRecipes', 
            'recentFavorites', 
            'recommendedRecipes'
        ));
    }
}