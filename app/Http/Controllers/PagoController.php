<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Pago;
use App\Models\Bono;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class PagoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $pagos = Pago::whereHas('reserva', function ($q) use ($usuario) {
            $q->where('id_usuario', $usuario->id);
        })->with(['reserva.servicio', 'reserva.perro', 'bono'])
          ->orderBy('created_at', 'desc')
          ->get();

        $bonos = Bono::where('id_usuario', $usuario->id)
                     ->where('activo', true)
                     ->where('usos_restantes', '>', 0)
                     ->get();

        $intent = $usuario->createSetupIntent();

        return view('pagos.index', compact('pagos', 'bonos', 'intent'));
    }

    public function checkout(Request $request, Reserva $reserva)
    {
        if ($reserva->id_usuario !== Auth::id()) {
            abort(403);
        }

        $servicios = $reserva->servicio;
        $noches = $reserva->fecha_entrada->diffInDays($reserva->fecha_salida);
        $precioBase = $noches * $servicios->precio_base;

        [$descuento, $bono] = $this->calcularDescuento($precioBase, $request->bono_id);

        $total = max(0, $precioBase - $descuento);
        $intent = Auth::user()->createSetupIntent([
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);

        return view('pagos.checkout', compact('reserva', 'total', 'precioBase', 'descuento', 'bono', 'intent', 'noches'));
    }

    public function procesar(Request $request, Reserva $reserva)
    {
        if ($reserva->id_usuario !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $noches = $reserva->fecha_entrada->diffInDays($reserva->fecha_salida);
        $precioBase = $noches * $reserva->servicio->precio_base;

        [$descuento, $bono] = $this->calcularDescuento($precioBase, $request->bono_id);

        $total = max(0.50, $precioBase - $descuento);

        try {
            $usuario = Auth::user();

            // Crear o recuperar cliente en Stripe
            $usuario->createOrGetStripeCustomer();

            // Adjuntar el payment method al cliente
            $usuario->addPaymentMethod($request->payment_method);

            // Crear PaymentIntent manualmente
            $stripe = new StripeClient(config('cashier.secret'));

            $paymentIntent = $stripe->paymentIntents->create([
                'amount'         => (int) round($total * 100),
                'currency'       => 'eur',
                'customer'       => $usuario->stripe_id,
                'payment_method' => $request->payment_method,
                'description'    => 'Reserva Rinconcito Perruno — ' . $reserva->perro->nombre,
                'confirm'        => true,
                'automatic_payment_methods' => [
                    'enabled'         => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            Pago::create([
                'importe'           => $total,
                'metodo'            => 'tarjeta',
                'estado'            => 'completado',
                'stripe_payment_id' => $paymentIntent->id,
                'fecha_pago'        => now(),
                'id_reserva'        => $reserva->id,
                'id_bono'           => $bono?->id,
            ]);

            if ($bono) {
                $bono->decrement('usos_restantes');
                if ($bono->usos_restantes <= 0) {
                    $bono->update(['activo' => false]);
                }
            }

            $reserva->update(['estado' => 'confirmada']);

            return redirect()->route('pagos.exito', $reserva->id);

        } catch (\Exception $e) {
            Log::error('Error en pago de reserva #' . $reserva->id, [
                'exception' => $e->getMessage(),
                'user_id'   => Auth::id(),
            ]);

            return redirect()->back()
                ->with('error', 'Ha ocurrido un error al procesar el pago. Por favor, inténtalo de nuevo o contacta con soporte.')
                ->withInput();
        }
    }

    public function exito(Reserva $reserva)
    {
        if ($reserva->id_usuario !== Auth::id()) {
            abort(403);
        }

        $pago = Pago::where('id_reserva', $reserva->id)->first();
        return view('pagos.exito', compact('reserva', 'pago'));
    }

    private function calcularDescuento(float $precioBase, ?int $bonoId): array
    {
        $descuento = 0;
        $bono = null;

        if ($bonoId) {
            $bono = Bono::where('id', $bonoId)
                        ->where('id_usuario', Auth::id())
                        ->where('activo', true)
                        ->where('usos_restantes', '>', 0)
                        ->where(function ($q) {
                            $q->whereNull('fecha_expiracion')
                              ->orWhere('fecha_expiracion', '>', now());
                        })
                        ->first();

            if ($bono) {
                $descuento = $bono->descuento_porcentaje > 0
                    ? $precioBase * ($bono->descuento_porcentaje / 100)
                    : $bono->descuento_fijo;
            }
        }

        return [$descuento, $bono];
    }
}