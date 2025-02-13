<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyRecipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'nama_makanan', 
        'gambar', 
        'waktu_memasak', 
        'porsi', 
        'jenis_hidangan', 
        'bahan', 
        'instruksi_memasak'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function boot()
{
    parent::boot();

    // Log setiap kali model diakses
    static::retrieved(function ($model) {
        \Log::info('Resep Diakses', [
            'ID Resep' => $model->id,
            'Nama Makanan' => $model->nama_makanan,
            'ID Pengguna' => $model->user_id
        ]);
    });
}
}