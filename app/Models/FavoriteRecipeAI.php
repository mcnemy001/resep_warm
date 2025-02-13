<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteRecipeAI extends Model
{
    use HasFactory;

    protected $table = 'favorite_recipes_AI';

    protected $fillable = [
       'user_id', 'food_name', 'ingredients', 'instructions', 'dish_type', 'cook_time', 'serving_size', 'recipe_image'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
    ];
}
