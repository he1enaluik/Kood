<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_json(false, 'Lubatud on ainult POST päring.', 405);
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

if ($firstname === '' || $lastname === '' || $email === '' || $phone === '' || $address === '' || $city === '' || $postcode === '') {
    api_json(false, 'Palun täida kõik kohustuslikud väljad.', 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    api_json(false, 'Sisesta kehtiv e-posti aadress.', 422);
}

if (!is_array($cart) || count($cart) === 0) {
    api_json(false, 'Ostukorv on tühi.', 422);
}

$subtotal = (float) ($data['subtotal'] ?? 0);
$shipping = (float) ($data['shipping'] ?? 0);
$total = (float) ($data['total'] ?? 0);

$payload = [
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

api_store_message('order', $payload);

$lines = ["Uus tellimus - Tarukoda\n", "KLIENDI ANDMED", '---------------'];
$lines[] = 'Nimi: ' . $firstname . ' ' . $lastname;
$lines[] = 'E-post: ' . $email;
$lines[] = 'Telefon: ' . $phone;
$lines[] = 'Aadress: ' . $address . ', ' . $postcode . ' ' . $city;

if ($notes !== '') {
    $lines[] = 'Märkused: ' . $notes;
}

$lines[] = '';
$lines[] = 'TOOTED';
$lines[] = '------';

foreach ($cart as $item) {
    $name = api_sanitize((string) ($item['name'] ?? 'Toode'), 100);
    $quantity = (int) ($item['quantity'] ?? 1);
    $lineTotal = number_format((float) ($item['lineTotal'] ?? 0), 2, ',', ' ');
    $lines[] = '- ' . $name . ' × ' . $quantity . ' = ' . $lineTotal . ' €';
}

$lines[] = '';
$lines[] = 'Vahesumma: ' . number_format($subtotal, 2, ',', ' ') . ' €';
$lines[] = 'Tarne: ' . number_format($shipping, 2, ',', ' ') . ' €';
$lines[] = 'Kokku: ' . number_format($total, 2, ',', ' ') . ' €';
$lines[] = '';
$lines[] = '---';
$lines[] = 'Saadetud: ' . date('d.m.Y H:i');

$subject = 'Tarukoda tellimus: ' . $firstname . ' ' . $lastname;
$body = implode("\n", $lines);

$mailSent = api_send_mail(api_mail_to(), $subject, $body, $email);

$message = $mailSent
    ? 'Tellimus esitatud! Võtame sinuga peagi ühendust.'
    : 'Tellimus salvestatud! (E-posti server pole kohalikult seadistatud — tellimus on failis api/inbox/order/)';

api_json(true, $message);
