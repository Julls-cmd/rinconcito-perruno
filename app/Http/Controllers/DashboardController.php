<?php

namespace App\Http\Controllers;

use App\Models\Bono;
use App\Models\Perro;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if ($usuario->hasRole('admin')) {
            return redirect()->route('admin.index');
        }

        $reservas = Reserva::where('id_usuario', $usuario->id)
            ->with(['perro', 'servicio'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $perros = Perro::where('id_usuario', $usuario->id)->get();
        $bonos = Bono::where('id_usuario', $usuario->id)
            ->where('activo', true)
            ->where('usos_restantes', '>', 0)
            ->get();

        return view('dashboard', compact('usuario', 'reservas', 'perros', 'bonos'));
    }
}
