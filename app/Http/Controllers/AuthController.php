<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Picqer\Barcode\BarcodeGeneratorSVG;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validación de datos (Añadimos 'confirmed' para la contraseña y quitamos la foto)
        $request->validate([
            'tipo' => 'required|in:estudiante,personal',
            'nombre_completo' => 'required|string|max:255',
            'adscripcion' => 'required|string',
            'numero_id' => 'required|string',
            'correo_electronico' => 'required|email',
            'password' => 'required|string|min:8|confirmed', // Validará contra 'password_confirmation'
        ]);

        // 2. Insertar en la base de datos dependiendo del tipo de usuario
        if ($request->tipo === 'estudiante') {
            DB::table('estudiantes')->insert([
                'nombre_completo' => $request->nombre_completo,
                'carrera' => $request->adscripcion,
                'numero_control' => $request->numero_id,
                'correo_electronico' => $request->correo_electronico,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                // Nota: La columna 'foto' se actualizará después desde el panel de edición
            ]);
        } else {
            DB::table('personal')->insert([
                'nombre_completo' => $request->nombre_completo,
                'departamento_adscripcion' => $request->adscripcion,
                'numero_empleado' => $request->numero_id,
                'correo_electronico' => $request->correo_electronico,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                // Nota: En caso de que personal también lleve foto después, se actualiza igual
            ]);
        }

        // 3. Redirigir al login
        return redirect()->route('login')->with('success', '¡Registro exitoso! Inicia sesión para continuar.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'numero_id' => 'required',
            'password' => 'required',
        ]);

        $tabla = ($request->tipo === 'estudiante') ? 'estudiantes' : 'personal';
        $columna_id = ($request->tipo === 'estudiante') ? 'numero_control' : 'numero_empleado';

        // 1. MEJORA: Hacemos que si es Personal, busque por No. Empleado O por el Correo (para que detecte las palabras clave)
        if ($request->tipo === 'personal') {
            $user = DB::table($tabla)
                ->where('numero_empleado', $request->numero_id)
                ->orWhere('correo_electronico', $request->numero_id)
                ->first();
        } else {
            $user = DB::table($tabla)->where($columna_id, $request->numero_id)->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {
            // VITAL: Guardamos ID y TIPO para que el dashboard sepa a quién buscar
            session([
                'user_id' => $user->id,
                'user_type' => $request->tipo
            ]);

            // 2. REDIRECCIÓN INTELIGENTE SEGÚN EL ROL
            if ($request->tipo === 'personal') {
                if ($user->correo_electronico === 'admin') {
                    // Si es el Administrador, lo mandamos al panel de usuarios
                    return redirect()->route('admin.usuarios');
                }

                if ($user->correo_electronico === 'colaborador') {
                    // Si es Comunicación/Difusión, lo mandamos a sellar/escanear
                    return redirect()->route('admin.escaner');
                }

                // NUEVO: Si es el Guardia de Seguridad, lo mandamos a su app móvil
                if ($user->correo_electronico === 'seguridad') {
                    return redirect()->route('guardia.panel');
                }
            }

            // 3. Si es un Estudiante o Personal normal, va a ver su tarjetón
            return redirect()->route('estudiante.dashboard');
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas.']);
    }

    public function dashboard()
    {
        $userId = session('user_id');
        $userType = session('user_type'); // Recuperamos el tipo (estudiante/personal)

        if (!$userId) {
            return redirect()->route('login');
        }

        // Buscamos en la tabla correcta según el tipo de sesión
        $tabla = ($userType === 'estudiante') ? 'estudiantes' : 'personal';
        $user = DB::table($tabla)->where('id', $userId)->first();

        // El tarjetón se busca por el estudiante_id (o personal_id si decides ampliarlo)
        $tarjeton = DB::table('tarjetones')->where('estudiante_id', $userId)->first();

        $barcode = null;
        if ($tarjeton) {
            $generator = new BarcodeGeneratorSVG();
            // Generamos el código con un tamaño mayor para que el celular lo lea fácil
            $barcode = $generator->getBarcode($tarjeton->folio, $generator::TYPE_CODE_128, 2, 60);
        }

        return view('estudiante.dashboard', compact('tarjeton', 'user', 'barcode'));
    }


    // FUNCIÓN PARA MOSTRAR LA VISTA DE EDICIÓN
    public function editTarjeton($id)
    {
        $userId = session('user_id');
        $tarjeton = DB::table('tarjetones')->where('id', $id)->first();

        // Buscamos al usuario sea estudiante o personal
        $user = DB::table('estudiantes')->where('id', $userId)->first();
        if (!$user) {
            $user = DB::table('personal')->where('id', $userId)->first();
        }

        return view('estudiante.editar_tarjeton', compact('tarjeton', 'user'));
    }

    // FUNCIÓN PARA GUARDAR LOS CAMBIOS
    public function updateTarjeton(Request $request, $id)
    {
        $userId = session('user_id');

        // Verificamos si es estudiante o personal para saber qué tabla actualizar
        $isEstudiante = DB::table('estudiantes')->where('id', $userId)->exists();
        $tablaUser = $isEstudiante ? 'estudiantes' : 'personal';

        // 1. Procesar la foto si se subió una nueva
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos_perfil', 'public');
        }

        // 2. Preparar los datos del usuario a actualizar
        $userData = [
            'nombre_completo' => $request->nombre_completo,
        ];

        if ($fotoPath) {
            $userData['foto'] = $fotoPath; // Solo actualiza la foto si subió una nueva
        }

        // Dependiendo del tipo, guardamos carrera/departamento y su respectivo ID
        if ($isEstudiante) {
            $userData['carrera'] = $request->adscripcion;
            $userData['numero_control'] = $request->numero_id;
        } else {
            $userData['departamento_adscripcion'] = $request->adscripcion;
            $userData['numero_empleado'] = $request->numero_id;
        }

        // Actualizamos al usuario
        DB::table($tablaUser)->where('id', $userId)->update($userData);

        // 3. Actualizar los datos del vehículo
        DB::table('tarjetones')->where('id', $id)->update([
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'placas' => strtoupper($request->placas),
            'color' => $request->color,
            'updated_at' => now(),
        ]);

        return redirect()->route('estudiante.dashboard')->with('success', '¡Datos y foto actualizados correctamente!');
    }

    // Función para eliminar
    public function destroyTarjeton($id)
    {
        DB::table('tarjetones')->where('id', $id)->delete();
        return redirect()->route('estudiante.dashboard')->with('success', 'Tarjetón eliminado');
    }


    // Esta función es la que te falta (muestra el formulario)
    public function createTarjeton()
    {
        return view('estudiante.nuevo_tarjeton');
    }

    // Esta función guarda los datos en la base de datos
    public function storeTarjeton(Request $request)
    {
        $request->validate([
            'marca' => 'required',
            'modelo' => 'required',
            'placas' => 'required|unique:tarjetones,placas',
            'color' => 'required',
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Sesión expirada.']);
        }

        DB::table('tarjetones')->insert([
            'estudiante_id' => $userId,
            'folio' => "ITO-" . date('Y') . "-" . rand(1000, 9999),
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'placas' => strtoupper($request->placas),
            'color' => $request->color,
            'activo' => false,
            'vigencia' => date('Y-12-31'),
            'created_at' => now(),
        ]);

        return redirect()->route('estudiante.dashboard');
    }
}
