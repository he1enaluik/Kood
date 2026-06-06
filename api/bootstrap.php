<?php

declare(strict_types=1);

function api_json(bool $success, string $message, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function api_mail_to(): string
{
    $config = __DIR__ . '/mail-config.php';

    if (is_file($config)) {
        $settings = include $config;
        if (!empty($settings['to'])) {
            return $settings['to'];
        }
    }

    return 'info@tarukoda.ee';
}

function api_read_json_body(): array
{
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw ?: '', true);

    return is_array($decoded) ? $decoded : [];
}

function api_store_message(string $type, array $data): void
{
    $dir = __DIR__ . '/inbox/' . $type;

    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $filename = $dir . '/' . date('Y-m-d_His') . '-' . uniqid('', true) . '.json';
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function api_send_mail(string $to, string $subject, string $body, string $replyTo): bool
{
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'From: Tarukoda <noreply@tarukoda.ee>',
        'Reply-To: ' . $replyTo,
    ];

    return mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
}

function api_sanitize(string $value, int $maxLength = 5000): string
{
    return mb_substr(trim(strip_tags($value)), 0, $maxLength);
}
