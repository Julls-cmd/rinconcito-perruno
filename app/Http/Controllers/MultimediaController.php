<?php

namespace App\Http\Controllers;

use App\Models\Multimedia;
use App\Models\Perro;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MultimediaController extends Controller
{
    /**
     * Vista cliente: muestra la multimedia del perro indicado.
     * Verifica que el perro pertenece al usuario autenticado.
     */
    public function index(Perro $perro)
    {
        // Seguridad: el perro debe pertenecer al usuario autenticado
        if ($perro->id_usuario !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este contenido.');
        }

        $multimedia = Multimedia::with(['reserva', 'subidoPor'])
            ->where('id_perro', $perro->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('multimedia.index', compact('perro', 'multimedia'));
    }

    /**
     * Almacena un archivo multimedia. Solo admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:51200',
            'id_reserva' => 'required|exists:reservas,id',
            'id_perro' => 'required|exists:perros,id',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $reserva = Reserva::findOrFail($request->id_reserva);
        $idReserva = $reserva->id;

        $archivo = $request->file('archivo');
        $mimeType = $archivo->getMimeType();
        $tipo = str_starts_with($mimeType, 'image/') ? 'foto' : 'video';

        // Guardar en storage/app/public/multimedia/{id_reserva}/
        $ruta = $archivo->store("multimedia/{$idReserva}", 'public');

        Multimedia::create([
            'ruta_archivo' => $ruta,
            'tipo' => $tipo,
            'descripcion' => $request->descripcion,
            'id_reserva' => $idReserva,
            'id_perro' => $request->id_perro,
            'subido_por' => auth()->id(),
        ]);

        return redirect()->route('admin.multimedia')
            ->with('success', 'Archivo subido correctamente.');
    }

    /**
     * Elimina un archivo multimedia. Solo admin.
     */
    public function destroy(Multimedia $multimedia)
    {
        // Eliminar archivo físico del storage
        if (Storage::disk('public')->exists($multimedia->ruta_archivo)) {
            Storage::disk('public')->delete($multimedia->ruta_archivo);
        }

        $multimedia->delete();

        return redirect()->route('admin.multimedia')
            ->with('success', 'Archivo eliminado correctamente.');
    }

    /**
     * Vista admin: gestión de multimedia.
     */
    public function adminIndex()
    {
        // Todas las reservas con su perro y usuario para el select del formulario
        $reservas = Reserva::with(['perro', 'usuario'])
            ->orderBy('fecha_entrada', 'desc')
            ->paginate(20);

        // Toda la multimedia ordenada por más reciente, con sus relaciones
        $multimedia = Multimedia::with(['perro', 'reserva', 'subidoPor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.multimedia', compact('reservas', 'multimedia'));
    }
}
