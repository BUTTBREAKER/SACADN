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
        Schema::create('representantes', function (Blueprint $table) {
            $table->id('ci')->notNull();
            $table->string('nombres', 50)->notNull();
            $table->string('apellido', 50)->notNull();
            $table->date('fn')->notNull();
            $table->string('estado', 20)->notNull();
            $table->string('genero', 12)->notNull();
            $table->string('lugar', 20)->notNull();
            $table->string('telf', 15)->notNull();
            $table->string('direccion', 50)->notNull();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representantes');
    }
};
