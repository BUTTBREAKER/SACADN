<?php

use App\Models\periodos;
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
        Schema::create('secciones_anos', function (Blueprint $table) {
            $table->id('id_secc_ano');
            $table->string('ano', 5)->nullable();
            $table->string('seccion', 5)->nullable();
            $table->foreignIdFor(periodos::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secciones_anos');
    }
};
