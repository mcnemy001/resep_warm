<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\PasswordResetNotification;
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relasi yang akan ikut dihapus saat akun dihapus
    public function favoriteRecipes()
    {
        return $this->hasMany(FavoriteRecipe::class);
    }

    public function myRecipes()
    {
        return $this->hasMany(MyRecipe::class);
    }
    

public function sendPasswordResetNotification($token)
{
    $this->notify(new PasswordResetNotification($token));
}
}