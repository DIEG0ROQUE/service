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
        // 1. Crear la tabla visitas_externas si no existe
        if (!Schema::hasTable('visitas_externas')) {
            Schema::create('visitas_externas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_conductor');
                $table->string('placa');
                $table->string('motivo');
                $table->text('nota')->nullable();
                $table->string('foto')->nullable();
                $table->timestamps();
            });
        }

        // 2. Modificar la tabla personal para agregar foto si no existe
        if (Schema::hasTable('personal') && !Schema::hasColumn('personal', 'foto')) {
            Schema::table('personal', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('password');
            });
        }

        // 3. Modificar la tabla tarjetones para agregar columnas faltantes
        if (Schema::hasTable('tarjetones')) {
            Schema::table('tarjetones', function (Blueprint $table) {
                if (!Schema::hasColumn('tarjetones', 'contacto_emergencia_nombre')) {
                    $table->string('contacto_emergencia_nombre')->nullable()->after('color');
                }
                if (!Schema::hasColumn('tarjetones', 'contacto_emergencia_telefono')) {
                    $table->string('contacto_emergencia_telefono')->nullable()->after('contacto_emergencia_nombre');
                }
                if (!Schema::hasColumn('tarjetones', 'tipo_usuario')) {
                    $table->string('tipo_usuario')->default('estudiante')->after('estudiante_id');
                }
            });

            // 4. Actualizar registros existentes de tarjetones de manera inteligente
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
                    // Ambos existen! Comparamos fechas de creación
                    $dateTarjeton = \Carbon\Carbon::parse($tarjeton->created_at);
                    $dateEstudiante = \Carbon\Carbon::parse($estudiante->created_at);
                    $datePersonal = \Carbon\Carbon::parse($personal->created_at);

                    // El tarjetón pertenece a quien se creó antes de él
                    $estudianteValido = $dateEstudiante->lte($dateTarjeton);
                    $personalValido = $datePersonal->lte($dateTarjeton);

                    if ($estudianteValido && !$personalValido) {
                        $tipo = 'estudiante';
                    } elseif (!$estudianteValido && $personalValido) {
                        $tipo = 'personal';
                    } else {
                        // Si ambos son válidos o ambos no lo son, elegimos el de menor diferencia temporal
                        $diffEstudiante = abs($dateTarjeton->diffInSeconds($dateEstudiante));
                        $diffPersonal = abs($dateTarjeton->diffInSeconds($datePersonal));
                        $tipo = ($diffEstudiante < $diffPersonal) ? 'estudiante' : 'personal';
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
        if (Schema::hasColumn('tarjetones', 'tipo_usuario')) {
            Schema::table('tarjetones', function (Blueprint $table) {
                $table->dropColumn('tipo_usuario');
            });
        }
    }
};
