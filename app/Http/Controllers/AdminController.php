<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importamos Carbon para las fechas
use Illuminate\Support\Facades\Hash; // <-- NUEVO: Para verificar la contraseña
use Illuminate\Support\Facades\Auth; // <-- NUEVO: Para saber quién es el admin actual

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
            $tipoUsuario = $tarjeton->tipo_usuario ?? null;
            if (!$tipoUsuario) {
                // Fallback por si acaso
                $isEstudiante = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->exists();
                $tipoUsuario = $isEstudiante ? 'estudiante' : 'personal';
            }

            if ($tipoUsuario === 'estudiante') {
                $user = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->first();
                $identificador = $user ? $user->numero_control : 'N/A';
                $adscripcion = $user ? $user->carrera : 'N/A';
                $tipo = 'Estudiante';
            } else {
                $user = DB::table('personal')->where('id', $tarjeton->estudiante_id)->first();
                $identificador = $user ? ($user->numero_empleado ?? 'N/A') : 'N/A';
                $adscripcion = $user ? ($user->departamento_adscripcion ?? 'N/A') : 'N/A';
                $tipo = 'Personal';
            }

            // Formatear la vigencia si existe
            $vigenciaFormato = $tarjeton->vigencia ? Carbon::parse($tarjeton->vigencia)->format('d/m/Y') : 'Sin sellar';

            return response()->json([
                'success' => true,
                'tarjeton' => $tarjeton,
                'vigencia' => $vigenciaFormato,
                'nombre' => $user ? $user->nombre_completo : 'N/A',
                'identificador' => $identificador,
                'adscripcion' => $adscripcion,
                'tipo' => $tipo,
                'foto' => $user ? ($user->foto ?? null) : null
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
        // 1. Modificamos las consultas con Left Join para traer el estatus del tarjetón de cada quien sin colisión de IDs
        $estudiantes = DB::table('estudiantes')
            ->leftJoin('tarjetones', function($join) {
                $join->on('estudiantes.id', '=', 'tarjetones.estudiante_id')
                     ->where('tarjetones.tipo_usuario', '=', 'estudiante');
            })
            ->select(
                'estudiantes.id', 
                'estudiantes.nombre_completo', 
                'estudiantes.correo_electronico', 
                'estudiantes.carrera as adscripcion', 
                'estudiantes.numero_control as numero_id',
                'estudiantes.foto',
                DB::raw("'Estudiante' as tipo"), 
                'tarjetones.activo as estatus_tarjeton',
                'tarjetones.folio',
                'tarjetones.marca',
                'tarjetones.modelo',
                'tarjetones.placas',
                'tarjetones.color',
                'tarjetones.vigencia',
                'tarjetones.contacto_emergencia_nombre',
                'tarjetones.contacto_emergencia_telefono'
            )
            ->get();

        $personal = DB::table('personal')
            ->leftJoin('tarjetones', function($join) {
                $join->on('personal.id', '=', 'tarjetones.estudiante_id')
                     ->where('tarjetones.tipo_usuario', '=', 'personal');
            })
            ->select(
                'personal.id', 
                'personal.nombre_completo', 
                'personal.correo_electronico', 
                'personal.departamento_adscripcion as adscripcion', 
                'personal.numero_empleado as numero_id',
                'personal.foto',
                DB::raw("'Personal' as tipo"), 
                'tarjetones.activo as estatus_tarjeton',
                'tarjetones.folio',
                'tarjetones.marca',
                'tarjetones.modelo',
                'tarjetones.placas',
                'tarjetones.color',
                'tarjetones.vigencia',
                'tarjetones.contacto_emergencia_nombre',
                'tarjetones.contacto_emergencia_telefono'
            )
            ->get();

        $usuarios = $estudiantes->merge($personal);

        // 2. Contadores para las tarjetas superiores
        $totalEstudiantes = DB::table('estudiantes')->count();
        $totalPersonal = DB::table('personal')->count();
        $totalUsuarios = $totalEstudiantes + $totalPersonal;

        $tarjetonesActivos = DB::table('tarjetones')->where('activo', 1)->count();
        $tarjetonesPendientes = DB::table('tarjetones')->where('activo', 0)->count();

        return view('admin.usuarios', compact(
            'usuarios',
            'totalUsuarios',
            'totalEstudiantes',
            'totalPersonal',
            'tarjetonesActivos',
            'tarjetonesPendientes'
        ));
    }

    public function updatePassword(Request $request)
    {
        $tabla = $request->tipo === 'Estudiante' ? 'estudiantes' : 'personal';
        DB::table($tabla)->where('id', $request->id)->update([
            'password' => Hash::make($request->password),
            'updated_at' => now()
        ]);
        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    // 1. Muestra la vista móvil del guardia
    public function panelGuardia()
    {
        return view('guardia.panel');
    }

    // 2. Busca un tarjetón tecleando la placa, nombre, ID o correo
    public function buscarPorPlaca(Request $request)
    {
        $termino = trim($request->input('placa'));
        if (empty($termino)) {
            return response()->json(['success' => false, 'message' => '⚠️ Por favor ingresa un término de búsqueda']);
        }

        // 1. Intentar buscar primero por placa en tarjetones (normalizando guiones/espacios)
        $terminoLimpio = str_replace(['-', ' '], '', $termino);
        $tarjeton = DB::table('tarjetones')
            ->where('placas', $termino)
            ->orWhereRaw("REPLACE(REPLACE(placas, '-', ''), ' ', '') = ?", [strtoupper($terminoLimpio)])
            ->first();

        $user = null;
        $tipo = null;
        $identificador = null;
        $adscripcion = null;

        if ($tarjeton) {
            $tipoUsuario = $tarjeton->tipo_usuario ?? null;
            if (!$tipoUsuario) {
                // Fallback por si acaso
                $isEstudiante = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->exists();
                $tipoUsuario = $isEstudiante ? 'estudiante' : 'personal';
            }

            if ($tipoUsuario === 'estudiante') {
                $user = DB::table('estudiantes')->where('id', $tarjeton->estudiante_id)->first();
                if ($user) {
                    $identificador = $user->numero_control;
                    $adscripcion = $user->carrera;
                    $tipo = 'Estudiante';
                }
            } else {
                $user = DB::table('personal')->where('id', $tarjeton->estudiante_id)->first();
                if ($user) {
                    $identificador = $user->numero_empleado ?? 'N/A';
                    $adscripcion = $user->departamento_adscripcion ?? 'N/A';
                    $tipo = 'Personal';
                }
            }
        }

        // 2. Si no se encontró por placa, buscamos al usuario por Nombre, Correo o ID
        if (!$user) {
            // Buscar en estudiantes
            $estudiante = DB::table('estudiantes')
                ->where('nombre_completo', 'like', '%' . $termino . '%')
                ->orWhere('numero_control', $termino)
                ->orWhere('correo_electronico', $termino)
                ->first();

            if ($estudiante) {
                $tarjeton = DB::table('tarjetones')
                    ->where('estudiante_id', $estudiante->id)
                    ->where('tipo_usuario', 'estudiante')
                    ->first();
                if ($tarjeton) {
                    $user = $estudiante;
                    $identificador = $estudiante->numero_control;
                    $adscripcion = $estudiante->carrera;
                    $tipo = 'Estudiante';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ Estudiante encontrado (' . $estudiante->nombre_completo . '), pero no tiene tarjetón registrado.'
                    ]);
                }
            }
        }

        if (!$user) {
            // Buscar en personal
            $personal = DB::table('personal')
                ->where('nombre_completo', 'like', '%' . $termino . '%')
                ->orWhere('numero_empleado', $termino)
                ->orWhere('correo_electronico', $termino)
                ->first();

            if ($personal) {
                $tarjeton = DB::table('tarjetones')
                    ->where('estudiante_id', $personal->id)
                    ->where('tipo_usuario', 'personal')
                    ->first();
                if ($tarjeton) {
                    $user = $personal;
                    $identificador = $personal->numero_empleado ?? 'N/A';
                    $adscripcion = $personal->departamento_adscripcion ?? 'N/A';
                    $tipo = 'Personal';
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ Empleado encontrado (' . $personal->nombre_completo . '), pero no tiene tarjetón registrado.'
                    ]);
                }
            }
        }

        // 3. Responder
        if ($tarjeton && $user) {
            $vigenciaFormato = $tarjeton->vigencia ? \Carbon\Carbon::parse($tarjeton->vigencia)->format('d/m/Y') : 'Sin sellar';
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

        return response()->json(['success' => false, 'message' => '⚠️ No se encontró vehículo, placa, usuario o correo coincidente.']);
    }

    // 3. Guarda el registro del visitante externo y su foto
    public function registrarVisita(Request $request)
    {
        $rutaFoto = null;
        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('visitas', 'public');
        }

        DB::table('visitas_externas')->insert([
            'nombre_conductor' => $request->nombre_conductor,
            'placa' => strtoupper($request->placa),
            'motivo' => $request->motivo,
            'nota' => $request->nota,
            'foto' => $rutaFoto,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', '✅ Visita externa registrada correctamente.');
    }

    public function listaVisitas()
    {
        // Traemos las visitas ordenadas por fecha de creación (las más nuevas primero)
        $visitas = DB::table('visitas_externas')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.visitas', compact('visitas'));
    }

    // ====================================================================
    // NUEVA FUNCIÓN: ELIMINAR USUARIO CON CONFIRMACIÓN DE CONTRASEÑA
    // ====================================================================
    public function eliminarUsuario(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'tipo' => 'required|in:estudiante,personal',
            'password_admin' => 'required|string'
        ]);

        // 1. Buscamos al administrador usando el nombre correcto de tu sesión: 'user_id'
        $adminId = session('user_id');
        $admin = DB::table('personal')->where('id', $adminId)->first();

        // 2. Verificamos que exista y que la contraseña coincida
        if (!$admin || !Hash::check($request->password_admin, $admin->password)) {
            return back()->withErrors(['password_admin' => 'Contraseña de administrador incorrecta. Operación cancelada por seguridad.']);
        }

        $tabla = $request->tipo === 'estudiante' ? 'estudiantes' : 'personal';

        // 3. Borramos sus tarjetones primero para limpiar la base de datos de manera específica por rol
        DB::table('tarjetones')
            ->where('estudiante_id', $request->id)
            ->where('tipo_usuario', $request->tipo)
            ->delete();

        // 4. Borramos al usuario
        DB::table($tabla)->where('id', $request->id)->delete();

        return back()->with('success', 'Usuario y sus registros eliminados permanentemente.');
    }
}
