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
        Schema::create('profesores', function (Blueprint $table) {
            $table->id('ci')->notNull();
            $table->string('nombre', 50)->nullable();
            $table->string('apellido', 50)->nullable();
            $table->date('fn')->nullable();
            $table->string('estado', 20)->nullable();
            $table->string('lugar', 20)->nullable();
            $table->enum('genero', ['masculino', 'femenino'])->nullable();
            $table->string('telf', 15)->nullable();
            $table->string('direccion', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores');
    }
};
