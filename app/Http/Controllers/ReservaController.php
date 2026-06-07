<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Perro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function index()
    {
        $servicios = Servicio::where('activo', true)->get();
        $perros = Perro::where('id_usuario', Auth::id())->get();
        return view('reservas.index', compact('servicios', 'perros'));
    }

    public function disponibilidad()
    {
        $reservas = Reserva::whereIn('estado', ['confirmada', 'en_curso', 'pendiente'])
            ->get()
            ->map(function ($reserva) {
                return [
                    'title' => 'Ocupado',
                    'start' => $reserva->fecha_entrada->format('Y-m-d'),
                    'end' => $reserva->fecha_salida->addDay()->format('Y-m-d'),
                    'color' => '#8B4513',
                    'textColor' => '#F5E6D0',
                ];
            });

        return response()->json($reservas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_entrada' => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'  => ['required', 'date', 'after:fecha_entrada'],
            'id_servicio'   => ['required', 'exists:servicios,id'],
            'id_perro'      => ['required', 'exists:perros,id'],
            'direccion_recogida' => ['nullable', 'string', 'max:255'],
            'notas'         => ['nullable', 'string', 'max:500'],
        ]);

        // Seguridad: el perro debe pertenecer al usuario autenticado (evita IDOR)
        $perro = Perro::findOrFail($request->id_perro);
        if ($perro->id_usuario !== Auth::id()) {
            abort(403, 'Este perro no te pertenece.');
        }

        return DB::transaction(function () use ($request) {
            // Verificar disponibilidad
            $conflicto = Reserva::whereIn('estado', ['confirmada', 'en_curso', 'pendiente'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('fecha_entrada', [$request->fecha_entrada, $request->fecha_salida])
                          ->orWhereBetween('fecha_salida', [$request->fecha_entrada, $request->fecha_salida])
                          ->orWhere(function ($q) use ($request) {
                              $q->where('fecha_entrada', '<=', $request->fecha_entrada)
                                ->where('fecha_salida', '>=', $request->fecha_salida);
                          });
                })
                ->lockForUpdate()
                ->exists();

            if ($conflicto) {
                return redirect()->back()
                    ->with('error', 'Las fechas seleccionadas no están disponibles. Por favor elige otras fechas.')
                    ->withInput();
            }

            Reserva::create([
                'fecha_entrada'      => $request->fecha_entrada,
                'fecha_salida'       => $request->fecha_salida,
                'estado'             => 'pendiente',
                'id_usuario'         => Auth::id(),
                'id_perro'           => $request->id_perro,
                'id_servicio'        => $request->id_servicio,
                'direccion_recogida' => $request->direccion_recogida,
                'notas'              => $request->notas,
            ]);

            return redirect()->route('dashboard')
                ->with('success', '¡Reserva realizada correctamente! Nos pondremos en contacto contigo para confirmarla.');
        });
    }
}