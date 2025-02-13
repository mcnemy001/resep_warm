<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('favorite_recipes_AI', function (Blueprint $table) {
            $table->id();
            $table->string('food_name');
            $table->text('ingredients');
            $table->text('instructions');
            $table->string('dish_type')->nullable();
            $table->string('cook_time')->nullable();
            $table->string('serving_size')->nullable();
            $table->string('recipe_image')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_recipe_a_i_s');
    }
};
