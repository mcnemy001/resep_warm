<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavoriteRecipeAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FavoriteRecipeAIController extends Controller
{
    public function index()
    {
        // Only show favorites for the logged-in user
        $favorites = FavoriteRecipeAI::where('user_id', Auth::id())->get();
        return view('favorites.index_ai', compact('favorites'));
    }

    public function store(Request $request)
    {

        try {
            // **Periksa apakah pengguna sudah login**
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'You must be logged in to save favorites.');
            }
    
            // Validasi data
            $validatedData = $request->validate([
                'food_name' => 'required|string|max:255',
                'ingredients' => 'required',
                'instructions' => 'required',
                'dish_type' => 'nullable|string|max:100',
                'cook_time' => 'nullable|string|max:50',
                'serving_size' => 'nullable|string|max:50',
                'recipe_image' => 'nullable|string|max:255',
            ]);
    
            // Simpan data ke database
            FavoriteRecipeAI::create([
                'user_id' => Auth::id(), // **Pastikan user_id dikirim**
                'food_name' => $validatedData['food_name'],
                'ingredients' => $request->ingredients,
                'instructions' => $request->instructions,
                'dish_type' => $validatedData['dish_type'],
                'cook_time' => $validatedData['cook_time'],
                'serving_size' => $validatedData['serving_size'],
                'recipe_image' => $validatedData['recipe_image']
            ]);

                    // Notifikasi berhasil
        session()->flash('success', 'Recipe successfully added to favorites!');

        } catch (\Exception $e) {
            // Notifikasi gagal
            session()->flash('error', 'Failed to add recipe: ' . $e->getMessage());
        }

        return redirect()->back();
    }
    
    public function show(FavoriteRecipeAI $favorite)
    {
        // Ensure user can only view their own favorites
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->route('favorites.index_ai')->with('error', 'Unauthorized action.');
        }

        return view('favorites.show_ai', compact('favorite'));
    }

    public function edit(FavoriteRecipeAI $favorite)
    {
        // Ensure user can only edit their own favorites
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->route('favorites.index_ai')->with('error', 'Unauthorized action.');
        }

        return view('favorites.edit_ai', compact('favorite'));
    }

    public function update(Request $request, FavoriteRecipeAI $favorite)
    {
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->route('favorites.index_ai')->with('error', 'Unauthorized action.');
        }
    
        // Validasi input
        $validatedData = $request->validate([
            'food_name' => 'required|string|max:255',
            'ingredients' => 'required|array',
            'instructions' => 'required|array',
            'dish_type' => 'nullable|string|max:100',
            'cook_time' => 'nullable|string|max:50',
            'serving_size' => 'nullable|string|max:50',
            'new_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Hanya menerima upload gambar baru
        ]);
    
        // Periksa apakah ada gambar baru yang diunggah
        if ($request->hasFile('new_image')) {
            // Hapus gambar lama jika ada di penyimpanan
            if ($favorite->recipe_image && Str::startsWith($favorite->recipe_image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $favorite->recipe_image);
                Storage::disk('public')->delete($oldPath);
            }
    
            // Simpan gambar baru
            $imagePath = $request->file('new_image')->store('recipe-images', 'public');
            $recipeImage = Storage::url($imagePath);
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $recipeImage = $favorite->recipe_image;
        }
    
        // Update data resep
        $favorite->update([
            'food_name' => $validatedData['food_name'],
            'ingredients' => json_encode($validatedData['ingredients']),
            'instructions' => json_encode($validatedData['instructions']),
            'dish_type' => $validatedData['dish_type'],
            'cook_time' => $validatedData['cook_time'],
            'serving_size' => $validatedData['serving_size'],
            'recipe_image' => $recipeImage
        ]);
    
        return redirect()->route('favorites.show_ai', $favorite)
            ->with('status', 'Recipe updated successfully');
    }
    

    public function destroy(FavoriteRecipeAI $favorite)
    {
        // Make sure users can only delete their own favorites
        if ($favorite->user_id !== Auth::id()) {
            return redirect()->route('favorites.index_ai')->with('error', 'Unauthorized action.');
        }

        $favorite->delete();
        return redirect()->route('favorites.index_ai')->with('status', 'Recipe removed from favorites');
    }
}