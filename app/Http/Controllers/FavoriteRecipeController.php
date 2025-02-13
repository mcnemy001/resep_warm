<?php

namespace App\Http\Controllers;

use App\Models\FavoriteRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class FavoriteRecipeController extends Controller
{
    // Menampilkan daftar resep favorit
    public function index()
    {
        $favorites = FavoriteRecipe::where('user_id', Auth::id())->get();
        return view('favorites.index', compact('favorites'));
    }

    // Menampilkan detail resep favorit
    public function show($id)
    {
        $favorite = FavoriteRecipe::findOrFail($id);
        
        // Pastikan hanya pemilik yang bisa melihat
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin melihat resep ini');
        }

        return view('favorites.show', compact('favorite'));
    }

    // Menampilkan form edit resep favorit
    public function edit($id)
    {
        $favorite = FavoriteRecipe::findOrFail($id);
        
        // Pastikan hanya pemilik yang bisa edit
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin mengedit resep ini');
        }

        return view('favorites.edit', compact('favorite'));
    }

    // Proses update resep favorit
    public function update(Request $request, $id)
{
    $favorite = FavoriteRecipe::findOrFail($id);
    
    if ($favorite->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Anda tidak memiliki izin mengupdate resep ini');
    }

    $details = json_decode($favorite->recipe_details, true);

    // Update waktu masak dan porsi
    if ($request->has('cooking_time')) {
        $details['readyInMinutes'] = $request->input('cooking_time');
    }

    if ($request->has('servings')) {
        $details['servings'] = $request->input('servings');
    }

    // Update bahan-bahan
    if ($request->has('ingredients')) {
        $details['extendedIngredients'] = array_map(function($ingredient) {
            return [
                'name' => $ingredient['name'] ?? '',
                'amount' => $ingredient['amount'] ?? 1,
                'unit' => $ingredient['unit'] ?? 'pcs'
            ];
        }, $request->input('ingredients'));
    }

    // Update instruksi
    if ($request->has('instructions')) {
        $details['analyzedInstructions'] = [[
            'steps' => array_map(function($instruction, $index) {
                return [
                    'number' => $index + 1,
                    'step' => $instruction['step'] ?? ''
                ];
            }, $request->input('instructions'), array_keys($request->input('instructions')))
        ]];
    }

    $favorite->update([
        'recipe_details' => json_encode($details)
    ]);

    return redirect()->route('favorites.show', $favorite->id)
        ->with('status', 'Favorite recipe successfully updated!');
}

    // Menghapus resep dari favorit
    public function destroy($id)
{
    $favorite = FavoriteRecipe::findOrFail($id);
    
    // Pastikan hanya pemilik yang bisa hapus
    if ($favorite->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'You do not have permission to remove this recipe from favorites');
    }

    $favorite->delete();

    return redirect()->route('favorites.index')
        ->with('status', 'Recipe successfully removed from favorites');
}
    // Method untuk menghapus resep dari favorit
public function removeFromFavorites($id)
{
    $favorite = FavoriteRecipe::findOrFail($id);
    
    // Pastikan hanya pemilik yang bisa menghapus
    if ($favorite->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'You do not have permission to remove this recipe from favorites');
    }

    $favorite->delete();

    return redirect()->route('favorites.index')
        ->with('status', 'Recipe successfully removed from favorites!');
}
public function store(Request $request)
{
    $validatedData = $request->validate([
        'recipe_id' => 'required|integer',
        'recipe_title' => 'required|string',
        'recipe_image' => 'required|string',
    ]);

    try {
        $apiKey = env('SPOONACULAR_API_KEY');
        $response = Http::get("https://api.spoonacular.com/recipes/{$validatedData['recipe_id']}/information", [
            'apiKey' => $apiKey,
            'includeNutrition' => true  // Tambahkan nutrisi untuk detail lengkap
        ]);

        if ($response->successful()) {
            $recipeDetails = $response->json();

            $favoriteRecipe = FavoriteRecipe::create([
                'user_id' => Auth::id(),
                'recipe_id' => $validatedData['recipe_id'],
                'recipe_title' => $validatedData['recipe_title'],
                'recipe_image' => $validatedData['recipe_image'],
                'recipe_details' => json_encode($recipeDetails)  // Simpan SEMUA detail dari API
            ]);

            return redirect()->back()->with('success', 'Recipe successfully added to favorites');
        }

        return redirect()->back()->with('error', 'Failed to fetch recipe details');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
}