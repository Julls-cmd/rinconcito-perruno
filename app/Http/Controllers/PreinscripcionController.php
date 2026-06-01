<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preinscripcion;
use Illuminate\Support\Facades\Auth;

class PreinscripcionController extends Controller
{
    public function create()
    {
        return view('preinscripciones.create');
    }

    public function store(Request $request)
    {
        $isGuest = !Auth::check();

        $rules = [
            'nombre_perro'      => ['required', 'string', 'max:100'],
            'raza'              => ['required', 'string', 'max:100'],
            'edad'              => ['required', 'integer', 'min:0', 'max:30'],
            'peso'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'vacunas'           => ['required', 'in:0,1'],
            'temperamento'      => ['required', 'in:tranquilo,activo,agresivo,sociable'],
            'observaciones'     => ['nullable', 'string', 'max:1000'],
            'telefono_contacto' => ['nullable', 'string', 'max:15'],
        ];

        // Solo obligar datos de contacto si el usuario NO está autenticado
        if ($isGuest) {
            $rules['nombre_contacto'] = ['required', 'string', 'max:150'];
            $rules['email_contacto']  = ['required', 'email', 'max:150'];
        }

        $request->validate($rules);

        Preinscripcion::create([
            'nombre_perro'      => $request->nombre_perro,
            'raza'              => $request->raza,
            'edad'              => $request->edad,
            'peso'              => $request->peso,
            'vacunas'           => (bool) $request->vacunas,
            'temperamento'      => $request->temperamento,
            'observaciones'     => $request->observaciones,
            'estado'            => 'pendiente',
            'id_usuario'        => Auth::id(),
            'nombre_contacto'   => Auth::check() ? Auth::user()->name  : $request->nombre_contacto,
            'email_contacto'    => Auth::check() ? Auth::user()->email  : $request->email_contacto,
            'telefono_contacto' => Auth::check() ? Auth::user()->telefono : $request->telefono_contacto,
        ]);

        return redirect()->back()->with('success', '¡Preinscripción enviada correctamente! Nos pondremos en contacto contigo en menos de 24 horas.');
    }
}