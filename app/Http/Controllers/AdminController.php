<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Muestra la vista de la cámara
    public function escaner()
    {
        return view('admin.escaner');
    }

    // Recibe el folio escaneado y cambia el estatus
    public function validarTarjeton(Request $request)
    {
        $folio = $request->input('folio');

        // Buscamos el tarjetón por su folio (el código de barras)
        $tarjeton = DB::table('tarjetones')->where('folio', $folio)->first();

        if ($tarjeton) {
            // Si está activo (1), lo pasamos a inactivo (0), y viceversa
            $nuevoEstado = $tarjeton->activo ? 0 : 1;

            DB::table('tarjetones')
                ->where('id', $tarjeton->id)
                ->update(['activo' => $nuevoEstado, 'updated_at' => now()]);

            $mensaje = $nuevoEstado ? '✅ Tarjetón ACTIVADO con éxito' : '❌ Tarjetón INACTIVADO';

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'estado' => $nuevoEstado,
                'folio' => $folio
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '⚠️ Tarjetón no encontrado en el sistema'
        ]);
    }
}
