<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;
use App\Models\Perro;
use App\Models\Bono;

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