<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\OrderFormRequest;
use App\Mail\ContactFormMail;
use App\Mail\OrderFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function submitContact(ContactFormRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        $this->storeMessage('contact', $data);
        Mail::to(config('site.mail_to'))->send(new ContactFormMail($data));

        $message = 'Sõnum saadetud! Vastame esimesel võimalusel.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function submitOrder(OrderFormRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

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
