<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyRecipesTable extends Migration
{
    public function up()
    {
        Schema::create('my_recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_makanan');
            $table->string('gambar')->nullable();
            $table->integer('waktu_memasak')->nullable(); // dalam menit
            $table->integer('porsi')->nullable();
            $table->string('jenis_hidangan')->nullable();
            $table->text('bahan');
            $table->text('instruksi_memasak');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('my_recipes');
    }
}