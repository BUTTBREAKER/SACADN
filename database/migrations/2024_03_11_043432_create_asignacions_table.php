<?php

use App\Models\Estudiante;
use App\Models\materias;
use App\Models\profesores;
use App\Models\secciones_anos;
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
        Schema::create('asignacions', function (Blueprint $table) {
            $table->id('id_asig');
            $table->foreignIdFor(Estudiante::class);
            $table->foreignIdFor(profesores::class);
            $table->foreignIdFor(materias::class);
            $table->foreignIdFor(secciones_anos::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};
