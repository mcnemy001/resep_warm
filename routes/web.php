<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteRecipeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyRecipeController;
use App\Http\Controllers\ImageToRecipeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FavoriteRecipeAIController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');
                Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::post('/deleteakun', [ProfileController::class, 'deleteAccount'])
    ->name('deleteakun')
    ->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Resep
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/search', [RecipeController::class, 'searchByName'])->name('recipes.search');
    Route::get('/recipes/recommend', [RecipeController::class, 'recommendByIngredients'])->name('recipes.recommend');
    Route::get('/recipes/{id}', [RecipeController::class, 'getRecipeDetails'])->name('recipes.details');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Rute untuk Favorit
    Route::resource('favorites', FavoriteRecipeController::class);

     // Rute untuk favorit
     Route::delete('/favorites/{id}/remove', [FavoriteRecipeController::class, 'removeFromFavorites'])
     ->name('favorites.remove');
});

Route::resource('resep-saya', MyRecipeController::class)->except(['edit', 'update', 'destroy']);
Route::get('/cek-resep/{id}', [MyRecipeController::class, 'cekResep']);
Route::get('/resep-saya/{id}', [MyRecipeController::class, 'show'])
    ->name('resep-saya.show')
    ->middleware('auth');
    Route::resource('resep-saya', MyRecipeController::class);

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    // Image to Recipe routes
    Route::get('/image-to-recipe', [ImageToRecipeController::class, 'index'])->name('image-to-recipe.index');
    Route::post('/image-to-recipe/analyze', [ImageToRecipeController::class, 'analyze'])->name('image-to-recipe.analyze');
    Route::get('/image-to-recipe/result', [ImageToRecipeController::class, 'showResult'])->name('image-to-recipe.result');
    
    // Favorite Recipe AI routes
    Route::post('/favorites-ai/store', [FavoriteRecipeAIController::class, 'store'])->name('favorites.store_ai');
    Route::get('/favorites-ai', [FavoriteRecipeAIController::class, 'index'])->name('favorites.index_ai');
    Route::delete('/favorites-ai/{favorite}', [FavoriteRecipeAIController::class, 'destroy'])->name('favorites.remove_ai');

    Route::get('/favorites-ai/{favorite}', [FavoriteRecipeAIController::class, 'show'])->name('favorites.show_ai');
    Route::get('/favorites-ai/{favorite}/edit', [FavoriteRecipeAIController::class, 'edit'])->name('favorites.edit_ai');
    Route::put('/favorites-ai/{favorite}', [FavoriteRecipeAIController::class, 'update'])->name('favorites.update_ai');
    
});