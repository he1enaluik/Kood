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
$message = api_sanitize($data['message'] ?? '', 5000);

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    api_json(false, 'Sisesta kehtiv e-posti aadress.', 422);
}

if ($message === '') {
    api_json(false, 'Sõnum on kohustuslik.', 422);
}

$payload = [
    'firstname' => $firstname,
    'lastname' => $lastname,
    'email' => $email,
    'message' => $message,
    'sent_at' => date('c'),
];

api_store_message('contact', $payload);

$name = trim($firstname . ' ' . $lastname);
$subject = 'Tarukoda kontakt: ' . ($name !== '' ? $name : $email);
$body = "Uus kontaktivormi sõnum - Tarukoda\n\n"
    . 'Nimi: ' . ($name !== '' ? $name : '—') . "\n"
    . 'E-post: ' . $email . "\n\n"
    . "Sõnum:\n" . $message . "\n\n"
    . '---' . "\n"
    . 'Saadetud: ' . date('d.m.Y H:i');

$mailSent = api_send_mail(api_mail_to(), $subject, $body, $email);

if ($mailSent) {
    api_json(true, 'Sõnum saadetud! Vastame esimesel võimalusel.');
}

api_json(
    false,
    'E-posti server pole seadistatud. Kontrolli, et js/site-config.local.js sisaldab Web3Forms võtit (loo see aadressil mardomais7@gmail.com).',
    502
);
