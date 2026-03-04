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
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:estudiantes,correo_electronico|unique:personal,correo_electronico',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|min:8',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos_perfil', 'public');
        }

        // Lógica corregida: Eliminamos la duplicación y separamos por tipo
        if ($request->tipo === 'estudiante') {
            DB::table('estudiantes')->insert([
                'nombre_completo' => $request->nombre_completo,
                'numero_control' => $request->numero_id,
                'carrera' => $request->adscripcion,
                'correo_electronico' => $request->correo_electronico,
                'foto' => $fotoPath,
                'password' => Hash::make($request->password),
                'created_at' => now(),
            ]);
        } else {
            DB::table('personal')->insert([
                'nombre_completo' => $request->nombre_completo,
                'departamento_adscripcion' => $request->adscripcion,
                'numero_empleado' => $request->numero_id,
                'correo_electronico' => $request->correo_electronico,
                'foto' => $fotoPath,
                'password' => Hash::make($request->password),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('login')->with('success', '¡Registro exitoso!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'numero_id' => 'required',
            'password' => 'required',
        ]);

        $tabla = ($request->tipo === 'estudiante') ? 'estudiantes' : 'personal';
        $columna_id = ($request->tipo === 'estudiante') ? 'numero_control' : 'numero_empleado';

        $user = DB::table($tabla)->where($columna_id, $request->numero_id)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // VITAL: Guardamos ID y TIPO para que el dashboard sepa a quién buscar
            session([
                'user_id' => $user->id,
                'user_type' => $request->tipo
            ]);
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


    public function editTarjeton($id)
    {
        $tarjeton = DB::table('tarjetones')->where('id', $id)->first();
        return view('estudiante.editar_tarjeton', compact('tarjeton'));
    }

    // Función para procesar la actualización
    public function updateTarjeton(Request $request, $id)
    {
        $userId = session('user_id');

        // 1. Procesar la foto si se subió una nueva
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos_perfil', 'public');

            // Actualizamos la foto en la tabla de estudiantes
            DB::table('estudiantes')->where('id', $userId)->update([
                'foto' => $fotoPath
            ]);
        }

        // 2. Actualizar los datos del vehículo
        DB::table('tarjetones')->where('id', $id)->update([
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'placas' => strtoupper($request->placas),
            'color' => $request->color,
            'updated_at' => now(),
        ]);

        return redirect()->route('estudiante.dashboard')->with('success', '¡Datos y foto actualizados!');
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
