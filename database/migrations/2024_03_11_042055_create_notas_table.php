<?php

use App\Models\materias;
use App\Models\profesores;
use App\Models\Representante;
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
        Schema::create('notas', function (Blueprint $table) {
            $table->id('id_notas');
            $table->string('momento_notas', 45)->nullable();
            $table->foreignIdFor(Representante::class);
            $table->foreignIdFor(profesores::class);
            $table->foreignIdFor(materias::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
