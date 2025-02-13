<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteRecipesTable extends Migration
{
    public function up()
    {
        Schema::create('favorite_recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recipe_id');
            $table->string('recipe_title');
            $table->string('recipe_image')->nullable();
            $table->text('recipe_details')->nullable(); // Simpan detail tambahan
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('favorite_recipes');
    }
}