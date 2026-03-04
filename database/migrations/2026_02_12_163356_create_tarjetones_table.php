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
    Schema::create('tarjetones', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('estudiante_id'); // Relación con tu tabla estudiantes
        $table->string('folio')->unique();           // Para el código de barras
        $table->string('marca');
        $table->string('modelo');
        $table->string('placas')->unique();
        $table->string('color');
        $table->boolean('activo')->default(false);   // Por defecto inicia inactivo hasta revisión
        $table->date('vigencia');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetones');
    }
};
