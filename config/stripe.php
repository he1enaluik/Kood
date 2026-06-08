<?php

return [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'success_url' => env('STRIPE_SUCCESS_URL', env('APP_URL', 'http://localhost:8000') . '/tellimus-onnestus'),
    'cancel_url' => env('STRIPE_CANCEL_URL', env('APP_URL', 'http://localhost:8000') . '/tellimus'),
];
