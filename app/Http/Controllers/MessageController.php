<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\OrderFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function submitContact(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'firstname' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $this->storeMessage('contact', $data);
        Mail::to(config('site.mail_to'))->send(new ContactFormMail($data));

        $message = 'Sõnum saadetud! Vastame esimesel võimalusel.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function submitOrder(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.name' => ['required', 'string'],
            'cart.*.quantity' => ['required', 'integer', 'min:1'],
            'cart.*.lineTotal' => ['required', 'numeric', 'min:0'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'shipping' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ]);

        $this->storeMessage('order', $data);
        Mail::to(config('site.mail_to'))->send(new OrderFormMail($data));

        $message = 'Tellimus esitatud! Võtame sinuga peagi ühendust.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    private function storeMessage(string $type, array $data): void
    {
        $filename = sprintf('%s/%s-%s.json', $type, now()->format('Y-m-d_His'), uniqid());

        Storage::disk('local')->put(
            'messages/' . $filename,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
