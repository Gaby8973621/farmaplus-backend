<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->nullable(false);
            $table->text("descripcion")->nullable();
            //es para el menu, si va al menu
            $table->boolean("menu")->default(0);
            //para el orden que va a parecer en el menu
            $table->integer("orden")->default(1);
            $table->string('urlfoto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
