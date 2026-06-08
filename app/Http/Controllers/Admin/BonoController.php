<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bono;
use App\Models\User;
use Illuminate\Http\Request;

class BonoController extends Controller
{
    /**
     * Listado de bonos + formulario de creación.
     */
    public function index()
    {
        $usuarios = User::role('cliente')->orderBy('name')->get();
        $bonos = Bono::with('usuario')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bonos', compact('usuarios', 'bonos'));
    }

    /**
     * Crea un bono y lo asigna a un cliente registrado.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'           => ['required', 'string', 'max:100'],
            'descripcion'      => ['nullable', 'string', 'max:255'],
            'tipo'             => ['required', 'in:porcentaje,fijo'],
            'valor'            => ['required', 'numeric', 'min:0.01'],
            'usos_maximos'     => ['required', 'integer', 'min:1', 'max:100'],
            'fecha_expiracion' => ['nullable', 'date', 'after:today'],
            'id_usuario'       => ['required', 'exists:users,id'],
        ]);

        // Un porcentaje no puede superar el 100 %
        if ($datos['tipo'] === 'porcentaje' && $datos['valor'] > 100) {
            return redirect()->back()->withInput()
                ->withErrors(['valor' => 'El porcentaje no puede superar el 100 %.']);
        }

        $esPorcentaje = $datos['tipo'] === 'porcentaje';

        Bono::create([
            'nombre'               => $datos['nombre'],
            'descripcion'          => $datos['descripcion'] ?? null,
            'descuento_porcentaje' => $esPorcentaje ? $datos['valor'] : 0,
            'descuento_fijo'       => $esPorcentaje ? 0 : $datos['valor'],
            'usos_maximos'         => $datos['usos_maximos'],
            'usos_restantes'       => $datos['usos_maximos'],
            'fecha_expiracion'     => $datos['fecha_expiracion'] ?? null,
            'id_usuario'           => $datos['id_usuario'],
            'activo'               => true,
        ]);

        return redirect()->route('admin.bonos')
            ->with('success', 'Bono creado y asignado correctamente.');
    }

    /**
     * Elimina un bono.
     */
    public function destroy(Bono $bono)
    {
        $bono->delete();

        return redirect()->route('admin.bonos')
            ->with('success', 'Bono eliminado correctamente.');
    }
}
