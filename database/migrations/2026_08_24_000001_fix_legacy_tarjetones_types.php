<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('tarjetones')) {
            $tarjetones = DB::table('tarjetones')->get();
            foreach ($tarjetones as $tarjeton) {
                $estudiante = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->first();
                $personal = DB::table('personal')->where('id', $tarjeton->estudiante_id)->first();

                $tipo = 'estudiante'; // Default

                if ($estudiante && !$personal) {
                    $tipo = 'estudiante';
                } elseif (!$estudiante && $personal) {
                    $tipo = 'personal';
                } elseif ($estudiante && $personal) {
                    // Ambos existen! Usamos el algoritmo de puntuación avanzado
                    $scoreEstudiante = 0;
                    $scorePersonal = 0;

                    $dateTarjeton = \Carbon\Carbon::parse($tarjeton->created_at);
                    $dateUpdateTarjeton = \Carbon\Carbon::parse($tarjeton->updated_at);
                    $dateEstudiante = \Carbon\Carbon::parse($estudiante->created_at);
                    $datePersonal = \Carbon\Carbon::parse($personal->created_at);

                    // 1. Verificar si el usuario fue creado después de que el tarjetón fuera actualizado
                    // Añadimos una tolerancia de 60 segundos
                    if ($dateEstudiante->gt($dateUpdateTarjeton->addSeconds(60))) {
                        $scoreEstudiante -= 100;
                    }
                    if ($datePersonal->gt($dateUpdateTarjeton->addSeconds(60))) {
                        $scorePersonal -= 100;
                    }

                    // 2. Coincidencia de palabras en el contacto de emergencia
                    if (!empty($tarjeton->contacto_emergencia_nombre)) {
                        $contactoNormalizado = strtolower($tarjeton->contacto_emergencia_nombre);
                        
                        // Palabras comunes a ignorar
                        $ignorar = ['de', 'la', 'los', 'y', 'del', 'el', 'las', 'en'];

                        // Nombre estudiante
                        $nombreEstudianteWords = explode(' ', strtolower($estudiante->nombre_completo));
                        foreach ($nombreEstudianteWords as $word) {
                            $word = trim($word);
                            if (strlen($word) >= 4 && !in_array($word, $ignorar)) {
                                if (strpos($contactoNormalizado, $word) !== false) {
                                    $scoreEstudiante += 10;
                                }
                            }
                        }

                        // Nombre personal
                        $nombrePersonalWords = explode(' ', strtolower($personal->nombre_completo));
                        foreach ($nombrePersonalWords as $word) {
                            $word = trim($word);
                            if (strlen($word) >= 4 && !in_array($word, $ignorar)) {
                                if (strpos($contactoNormalizado, $word) !== false) {
                                    $scorePersonal += 10;
                                }
                            }
                        }
                    }

                    // 3. Proximidad de creación
                    $diffEstudianteCreated = abs($dateTarjeton->diffInSeconds($dateEstudiante));
                    $diffPersonalCreated = abs($dateTarjeton->diffInSeconds($datePersonal));
                    if ($diffEstudianteCreated <= 3600) {
                        $scoreEstudiante += 5;
                    }
                    if ($diffPersonalCreated <= 3600) {
                        $scorePersonal += 5;
                    }

                    $diffEstudianteUpdated = abs($dateUpdateTarjeton->diffInSeconds($dateEstudiante));
                    $diffPersonalUpdated = abs($dateUpdateTarjeton->diffInSeconds($datePersonal));
                    if ($diffEstudianteUpdated <= 3600) {
                        $scoreEstudiante += 5;
                    }
                    if ($diffPersonalUpdated <= 3600) {
                        $scorePersonal += 5;
                    }

                    // Decidir por puntuación más alta, o fallback a proximidad pura si hay empate
                    if ($scoreEstudiante > $scorePersonal) {
                        $tipo = 'estudiante';
                    } elseif ($scorePersonal > $scoreEstudiante) {
                        $tipo = 'personal';
                    } else {
                        // Empate: usar proximidad temporal de creación
                        $tipo = ($diffEstudianteCreated < $diffPersonalCreated) ? 'estudiante' : 'personal';
                    }
                }

                DB::table('tarjetones')
                    ->where('id', $tarjeton->id)
                    ->update(['tipo_usuario' => $tipo]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No destructivo
    }
};
