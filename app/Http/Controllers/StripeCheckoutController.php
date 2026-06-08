<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeCheckoutController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $secret = config('stripe.secret');

        if (!$secret) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe pole seadistatud.',
            ], 503);
        }

        $validated = $request->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.name' => ['required', 'string', 'max:100'],
            'cart.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'cart.*.lineTotal' => ['required', 'numeric', 'min:0'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'shipping' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0.5'],
        ]);

        Stripe::setApiKey($secret);

        $lineItems = [];

        foreach ($validated['cart'] as $item) {
            $quantity = (int) $item['quantity'];
            $lineTotal = round((float) $item['lineTotal'], 2);
            $unitAmount = (int) round(($lineTotal / max(1, $quantity)) * 100);

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => max(50, $unitAmount),
                ],
                'quantity' => $quantity,
            ];
        }

        if ($validated['shipping'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Tarne',
                    ],
                    'unit_amount' => (int) round($validated['shipping'] * 100),
                ],
                'quantity' => 1,
            ];
        }

        $pendingId = uniqid('ord_', true);
        $payload = array_merge($validated, [
            'pending_id' => $pendingId,
            'sent_at' => now()->toIso8601String(),
        ]);

        Storage::disk('local')->put(
            'orders/pending/' . $pendingId . '.json',
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $session = Session::create([
            'mode' => 'payment',
            'success_url' => config('stripe.success_url') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('stripe.cancel_url'),
            'customer_email' => $validated['email'],
            'line_items' => $lineItems,
            'metadata' => [
                'pending_id' => $pendingId,
                'customer_name' => $validated['firstname'] . ' ' . $validated['lastname'],
            ],
        ]);

        return response()->json([
            'success' => true,
            'url' => $session->url,
        ]);
    }
}
