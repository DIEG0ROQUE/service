<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importamos Carbon para las fechas

class AdminController extends Controller
{
    public function escaner()
    {
        return view('admin.escaner');
    }

    public function buscarTarjeton(Request $request)
    {
        // Limpiamos el folio por si la cámara escanea espacios ocultos
        $folio = trim($request->input('folio'));

        $tarjeton = DB::table('tarjetones')->where('folio', $folio)->first();

        if ($tarjeton) {
            $isEstudiante = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->exists();

            if ($isEstudiante) {
                $user = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->first();
                $identificador = $user->numero_control;
                $adscripcion = $user->carrera;
                $tipo = 'Estudiante';
            } else {
                $user = DB::table('personal')->where('id', $tarjeton->estudiante_id)->first();
                $identificador = $user->numero_empleado ?? 'N/A';
                $adscripcion = $user->departamento_adscripcion ?? 'N/A';
                $tipo = 'Personal';
            }

            // Formatear la vigencia si existe
            $vigenciaFormato = $tarjeton->vigencia ? Carbon::parse($tarjeton->vigencia)->format('d/m/Y') : 'Sin sellar';

            return response()->json([
                'success' => true,
                'tarjeton' => $tarjeton,
                'vigencia' => $vigenciaFormato,
                'nombre' => $user->nombre_completo,
                'identificador' => $identificador,
                'adscripcion' => $adscripcion,
                'tipo' => $tipo,
                'foto' => $user->foto ?? null
            ]);
        }

        return response()->json(['success' => false, 'message' => '⚠️ Tarjetón no encontrado']);
    }

    public function toggleEstatus(Request $request)
    {
        $folio = trim($request->input('folio'));
        $tarjeton = DB::table('tarjetones')->where('folio', $folio)->first();

        if ($tarjeton) {
            $nuevoEstado = $tarjeton->activo ? 0 : 1;

            // SOLUCIÓN AL ERROR:
            // Si activamos, damos 1 año.
            // Si desactivamos, dejamos la vigencia que ya tenía (o hoy) para que no sea NULL.
            $nuevaVigencia = $nuevoEstado ? now()->addYear() : ($tarjeton->vigencia ?? now());

            DB::table('tarjetones')->where('folio', $folio)->update([
                'activo' => $nuevoEstado,
                'vigencia' => $nuevaVigencia,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'estado' => $nuevoEstado,
                'vigencia' => \Carbon\Carbon::parse($nuevaVigencia)->format('d/m/Y')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No se encontró el folio.']);
    }


    public function listaUsuarios()
    {
        $estudiantes = DB::table('estudiantes')->select('id', 'nombre_completo', 'correo_electronico', 'carrera as adscripcion', DB::raw("'Estudiante' as tipo"))->get();
        $personal = DB::table('personal')->select('id', 'nombre_completo', 'correo_electronico', 'departamento_adscripcion as adscripcion', DB::raw("'Personal' as tipo"))->get();

        $usuarios = $estudiantes->merge($personal);
        return view('admin.usuarios', compact('usuarios'));
    }

    public function updatePassword(Request $request)
    {
        $tabla = $request->tipo === 'Estudiante' ? 'estudiantes' : 'personal';
        DB::table($tabla)->where('id', $request->id)->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'updated_at' => now()
        ]);
        return back()->with('success', 'Contraseña actualizada correctamente');
    }
}
