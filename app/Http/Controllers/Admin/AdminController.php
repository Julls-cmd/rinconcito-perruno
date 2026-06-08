<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perro;
use App\Models\Preinscripcion;
use App\Models\Reserva;
use App\Models\User;

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

    public function aprobarPreinscripcion(Preinscripcion $preinscripcion)
    {
        if ($preinscripcion->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta preinscripción ya ha sido procesada.');
        }

        // Resolver el dueño: el usuario que la envió o, si fue un visitante,
        // un cliente registrado con el mismo email de contacto.
        $propietario = $preinscripcion->id_usuario
            ? User::find($preinscripcion->id_usuario)
            : User::where('email', $preinscripcion->email_contacto)->first();

        // Si hay dueño, se da de alta el perro con los datos de la preinscripción
        // para que el cliente pueda reservar de inmediato.
        if ($propietario) {
            Perro::create([
                'nombre' => $preinscripcion->nombre_perro,
                'raza' => $preinscripcion->raza,
                'edad' => $preinscripcion->edad,
                'peso' => $preinscripcion->peso,
                'temperamento' => $preinscripcion->temperamento,
                'vacunas' => $preinscripcion->vacunas,
                'observaciones' => $preinscripcion->observaciones,
                'id_usuario' => $propietario->id,
            ]);
        }

        $preinscripcion->update(['estado' => 'aprobada']);

        $mensaje = $propietario
            ? "Preinscripción aprobada. El perro {$preinscripcion->nombre_perro} se ha añadido a la cuenta de {$propietario->name}."
            : 'Preinscripción aprobada. El perro se dará de alta cuando el cliente cree su cuenta con este email.';

        return redirect()->back()->with('success', $mensaje);
    }

    public function rechazarPreinscripcion(Preinscripcion $preinscripcion)
    {
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

    public function confirmarReserva(Reserva $reserva)
    {
        if ($reserva->estado !== 'pendiente') {
            return redirect()->back()->with('error', 'Esta reserva no se puede confirmar en su estado actual.');
        }

        $reserva->update(['estado' => 'confirmada']);

        return redirect()->back()->with('success', 'Reserva confirmada correctamente.');
    }

    public function cancelarReserva(Reserva $reserva)
    {
        if (! in_array($reserva->estado, ['pendiente', 'confirmada'])) {
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
