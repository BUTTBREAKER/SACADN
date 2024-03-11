<?php

use App\Models\Representante;
use Database\Seeders\RepresentanteSeeder;
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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id('ci')->notNull();
            $table->foreignIdFor(Representante::class);
            $table->string('nombre', 50)->notNull();
            $table->string('apellido', 50)->notNull();
            $table->date('fn')->notNull();
            $table->string('estado', 20)->notNull();
            $table->string('lugar', 20)->notNull();
            $table->enum('genero', ['masculino', 'femenino'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
