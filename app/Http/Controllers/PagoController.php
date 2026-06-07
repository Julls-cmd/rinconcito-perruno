<?php

namespace App\Http\Controllers;

use App\Models\Bono;
use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        session(['checkout_'.$reserva->id => [
            'bono_id' => $bono?->id,
            'total' => $total,
        ]]);

        return view('pagos.checkout', compact('reserva', 'total', 'precioBase', 'descuento', 'bono', 'intent', 'noches'));
    }

    public function procesar(Request $request, Reserva $reserva)
    {
        if ($reserva->id_usuario !== Auth::id()) {
            abort(403);
        }

        if ($reserva->pago()->exists()) {
            return redirect()->route('pagos.exito', $reserva->id);
        }

        $checkoutData = session('checkout_'.$reserva->id);

        if (! $checkoutData) {
            return redirect()->route('pagos.checkout', $reserva->id)
                ->with('error', 'La sesión de pago ha expirado. Por favor, inicia el proceso de nuevo.');
        }

        $total = $checkoutData['total'];
        $bonoId = $checkoutData['bono_id'];

        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $noches = $reserva->fecha_entrada->diffInDays($reserva->fecha_salida);
        $precioBase = $noches * $reserva->servicio->precio_base;

        [, $bono] = $this->calcularDescuento($precioBase, $bonoId);

        try {
            $usuario = Auth::user();

            DB::transaction(function () use ($request, $reserva, $usuario, $total, $bono) {
                // 1. Crear pago como pendiente ANTES de cobrar
                $pago = Pago::create([
                    'importe' => $total,
                    'metodo' => 'tarjeta',
                    'estado' => 'pendiente',
                    'fecha_pago' => now(),
                    'id_reserva' => $reserva->id,
                    'id_bono' => $bono?->id,
                ]);

                // 2. Operaciones Stripe
                $usuario->createOrGetStripeCustomer();
                $usuario->addPaymentMethod($request->payment_method);

                $stripe = new StripeClient(config('cashier.secret'));
                $paymentIntent = $stripe->paymentIntents->create([
                    'amount' => (int) round($total * 100),
                    'currency' => 'eur',
                    'customer' => $usuario->stripe_id,
                    'payment_method' => $request->payment_method,
                    'description' => 'Reserva Rinconcito Perruno — '.$reserva->perro->nombre,
                    'confirm' => true,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],
                ], [
                    'idempotency_key' => 'pago_reserva_'.$reserva->id,
                ]);

                // 3. Actualizar pago a completado
                $pago->update([
                    'estado' => 'completado',
                    'stripe_payment_id' => $paymentIntent->id,
                ]);

                // 4. Bono y reserva (igual que ahora)
                if ($bono) {
                    $bono->decrement('usos_restantes');
                    if ($bono->usos_restantes <= 0) {
                        $bono->update(['activo' => false]);
                    }
                }

                $reserva->update(['estado' => 'confirmada']);
            });

            session()->forget('checkout_'.$reserva->id);

            return redirect()->route('pagos.exito', $reserva->id);

        } catch (\Exception $e) {
            Log::error('Error en pago de reserva #'.$reserva->id, [
                'exception' => $e->getMessage(),
                'user_id' => Auth::id(),
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
