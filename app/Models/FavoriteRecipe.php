<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'recipe_id', 
        'recipe_title', 
        'recipe_image',
        'recipe_details'
    ];

    
    protected $casts = [
        'recipe_details' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}