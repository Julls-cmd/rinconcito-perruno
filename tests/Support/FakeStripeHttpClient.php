<?php

namespace Tests\Support;

use Stripe\HttpClient\ClientInterface;

/**
 * Cliente HTTP falso para la SDK de Stripe.
 *
 * Se registra con \Stripe\ApiRequestor::setHttpClient() para que NINGUNA
 * llamada (SetupIntent, PaymentIntent, Customer, PaymentMethod) salga a la red.
 * Devuelve respuestas JSON canónicas con código 200, suficientes para que
 * Laravel Cashier construya los objetos que las vistas necesitan.
 */
class FakeStripeHttpClient implements ClientInterface
{
    public function request($method, $absUrl, $headers, $params, $hasFile, $apiMode = 'v1', $maxNetworkRetries = null)
    {
        $object = 'setup_intent';
        $prefix = 'seti';

        if (str_contains($absUrl, 'payment_intents')) {
            $object = 'payment_intent';
            $prefix = 'pi';
        } elseif (str_contains($absUrl, 'payment_methods')) {
            $object = 'payment_method';
            $prefix = 'pm';
        } elseif (str_contains($absUrl, 'customers')) {
            $object = 'customer';
            $prefix = 'cus';
        }

        $id = $prefix.'_fake_'.bin2hex(random_bytes(8));

        $body = [
            'id' => $id,
            'object' => $object,
            'status' => 'succeeded',
            'client_secret' => $id.'_secret_fake',
            'amount' => 0,
            'currency' => 'eur',
            'livemode' => false,
            'created' => time(),
        ];

        return [json_encode($body), 200, []];
    }
}
