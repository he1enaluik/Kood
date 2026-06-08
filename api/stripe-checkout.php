<?php

declare(strict_types=1);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://127.0.0.1:5500',
    'http://localhost:5500',
    'http://127.0.0.1:8080',
    'http://localhost:8080',
    'http://127.0.0.1:8000',
    'http://localhost:8000',
];

if (in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Accept');
    header('Vary: Origin');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_json(false, 'Lubatud on ainult POST päring.', 405);
}

$configFile = __DIR__ . '/stripe-config.php';

if (!is_file($configFile)) {
    $configFile = dirname(__DIR__, 2) . '/api/stripe-config.php';
}

if (!is_file($configFile)) {
    api_json(false, 'Stripe pole seadistatud. Loo api/stripe-config.php fail.', 503);
}

$config = include $configFile;
$secretKey = trim((string) ($config['secret_key'] ?? ''));

if ($secretKey === '' || str_contains($secretKey, 'your_secret_key')) {
    api_json(false, 'Stripe salavõti puudub. Täida api/stripe-config.php.', 503);
}

$data = api_read_json_body();

$firstname = api_sanitize($data['firstname'] ?? '', 100);
$lastname = api_sanitize($data['lastname'] ?? '', 100);
$email = api_sanitize($data['email'] ?? '', 255);
$phone = api_sanitize($data['phone'] ?? '', 30);
$address = api_sanitize($data['address'] ?? '', 255);
$city = api_sanitize($data['city'] ?? '', 100);
$postcode = api_sanitize($data['postcode'] ?? '', 20);
$notes = api_sanitize($data['notes'] ?? '', 2000);
$cart = $data['cart'] ?? [];
$subtotal = round((float) ($data['subtotal'] ?? 0), 2);
$shipping = round((float) ($data['shipping'] ?? 0), 2);
$total = round((float) ($data['total'] ?? 0), 2);

if ($firstname === '' || $lastname === '' || $email === '' || $phone === '' || $address === '' || $city === '' || $postcode === '') {
    api_json(false, 'Palun täida kõik kohustuslikud väljad.', 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    api_json(false, 'Sisesta kehtiv e-posti aadress.', 422);
}

if (!is_array($cart) || count($cart) === 0) {
    api_json(false, 'Ostukorv on tühi.', 422);
}

if ($total < 0.5) {
    api_json(false, 'Tellimuse summa on liiga väike.', 422);
}

$lineItems = [];
$index = 0;

foreach ($cart as $item) {
    if (!is_array($item)) {
        continue;
    }

    $name = api_sanitize((string) ($item['name'] ?? 'Toode'), 100);
    $quantity = max(1, min(20, (int) ($item['quantity'] ?? 1)));
    $lineTotal = round((float) ($item['lineTotal'] ?? 0), 2);
    $unitAmount = (int) round(($lineTotal / $quantity) * 100);

    $lineItems[$index] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => $name,
            ],
            'unit_amount' => max(50, $unitAmount),
        ],
        'quantity' => $quantity,
    ];
    $index++;
}

if ($shipping > 0) {
    $lineItems[$index] = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => 'Tarne',
            ],
            'unit_amount' => (int) round($shipping * 100),
        ],
        'quantity' => 1,
    ];
}

$pendingId = uniqid('ord_', true);
$payload = [
    'pending_id' => $pendingId,
    'firstname' => $firstname,
    'lastname' => $lastname,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'city' => $city,
    'postcode' => $postcode,
    'notes' => $notes,
    'cart' => $cart,
    'subtotal' => $subtotal,
    'shipping' => $shipping,
    'total' => $total,
    'sent_at' => date('c'),
];

api_store_message('order-pending', $payload);

$successUrl = rtrim((string) ($config['success_url'] ?? ''), '/');
$cancelUrl = rtrim((string) ($config['cancel_url'] ?? ''), '/');

if ($successUrl === '' || $cancelUrl === '') {
    api_json(false, 'Stripe success/cancel URL puudub konfiguratsioonis.', 503);
}

$params = [
    'mode' => 'payment',
    'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $cancelUrl,
    'customer_email' => $email,
    'metadata' => [
        'pending_id' => $pendingId,
        'customer_name' => $firstname . ' ' . $lastname,
    ],
    'line_items' => $lineItems,
];

$fields = stripe_flatten_fields($params);
$fields = array_merge($fields, stripe_flatten_line_items($lineItems));

$response = stripe_api_request($secretKey, 'checkout/sessions', $fields);

if (!$response['ok']) {
    api_json(false, $response['message'] ?? 'Stripe makse alustamine ebaõnnestus.', 502);
}

http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => true,
    'url' => $response['data']['url'] ?? '',
], JSON_UNESCAPED_UNICODE);
exit;

function stripe_flatten_fields(array $params, string $prefix = ''): array
{
    $result = [];

    foreach ($params as $key => $value) {
        if ($key === 'line_items') {
            continue;
        }

        $fieldKey = $prefix === '' ? (string) $key : $prefix . '[' . $key . ']';

        if (is_array($value)) {
            $result = array_merge($result, stripe_flatten_fields($value, $fieldKey));
            continue;
        }

        $result[$fieldKey] = (string) $value;
    }

    return $result;
}

function stripe_flatten_line_items(array $lineItems): array
{
    $result = [];

    foreach ($lineItems as $index => $item) {
        $prefix = 'line_items[' . $index . ']';
        $flattened = stripe_flatten_fields($item, $prefix);
        $result = array_merge($result, $flattened);
    }

    return $result;
}

function stripe_api_request(string $secretKey, string $path, array $fields): array
{
    $curl = curl_init('https://api.stripe.com/v1/' . ltrim($path, '/'));
    $caBundle = __DIR__ . '/cacert.pem';

    $options = [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $secretKey,
        ],
        CURLOPT_POSTFIELDS => http_build_query($fields),
    ];

    if (is_file($caBundle)) {
        $options[CURLOPT_CAINFO] = $caBundle;
    }

    curl_setopt_array($curl, $options);

    $raw = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);
    curl_close($curl);

    if ($raw === false) {
        return [
            'ok' => false,
            'message' => $curlError !== '' ? $curlError : 'Stripe API viga.',
        ];
    }

    $decoded = json_decode($raw, true);

    if ($status >= 400 || !is_array($decoded)) {
        return [
            'ok' => false,
            'message' => $decoded['error']['message'] ?? 'Stripe API viga.',
        ];
    }

    return [
        'ok' => true,
        'data' => $decoded,
    ];
}
