<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preinscripcion;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Perro;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsuarios = User::role('cliente')->count();
        $totalReservas = Reserva::count();
        $reservasPendientes = Reserva::where('estado', 'pendiente')->count();
        $preinscripcionesPendientes = Preinscripcion::where('estado', 'pendiente')->count();
        $totalPerros = Perro::count();
        $ultimasReservas = Reserva::with(['usuario', 'perro', 'servicio'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $ultimasPreinscripciones = Preinscripcion::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'totalUsuarios',
            'totalReservas',
            'reservasPendientes',
            'preinscripcionesPendientes',
            'totalPerros',
            'ultimasReservas',
            'ultimasPreinscripciones'
        ));
    }

    public function preinscripciones()
    {
        $preinscripciones = Preinscripcion::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.preinscripciones', compact('preinscripciones'));
    }

    public function aprobarPreinscripcion($id)
    {
        $preinscripcion = Preinscripcion::findOrFail($id);

        if ($preinscripcion->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta preinscripción ya ha sido procesada.');
        }

        $preinscripcion->update(['estado' => 'aprobada']);
        return redirect()->back()->with('success', 'Preinscripción aprobada correctamente.');
    }

    public function rechazarPreinscripcion($id)
    {
        $preinscripcion = Preinscripcion::findOrFail($id);

        if ($preinscripcion->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta preinscripción ya ha sido procesada.');
        }

        $preinscripcion->update(['estado' => 'rechazada']);
        return redirect()->back()->with('success', 'Preinscripción rechazada.');
    }

    public function reservas()
    {
        $reservas = Reserva::with(['usuario', 'perro', 'servicio'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.reservas', compact('reservas'));
    }

    public function confirmarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);

        if ($reserva->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta reserva no se puede confirmar en su estado actual.');
        }

        $reserva->update(['estado' => 'confirmada']);
        return redirect()->back()->with('success', 'Reserva confirmada correctamente.');
    }

    public function cancelarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);

        if (!in_array($reserva->estado, ['pendiente', 'confirmada'])) {
            return redirect()->back()->with('error', 'Esta reserva no se puede cancelar en su estado actual.');
        }

        $reserva->update(['estado' => 'cancelada']);
        return redirect()->back()->with('success', 'Reserva cancelada.');
    }

    public function usuarios()
    {
        $usuarios = User::role('cliente')->with('perros')->paginate(10);
        return view('admin.usuarios', compact('usuarios'));
    }
}