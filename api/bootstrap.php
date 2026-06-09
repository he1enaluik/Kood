<?php

declare(strict_types=1);

ini_set('display_errors', '0');

function api_json(bool $success, string $message, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $success, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function api_mail_config(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $configFile = __DIR__ . '/mail-config.php';
    $config = is_file($configFile) ? include $configFile : [];

    return is_array($config) ? $config : [];
}

function api_mail_to(): string
{
    $settings = api_mail_config();

    if (!empty($settings['to'])) {
        return $settings['to'];
    }

    return 'info@tarukoda.ee';
}

function api_web3forms_key(): string
{
    $settings = api_mail_config();

    return trim((string) ($settings['web3forms_key'] ?? ''));
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

    return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
}

function api_send_web3forms(array $payload): bool
{
    $accessKey = api_web3forms_key();

    if ($accessKey === '') {
        return false;
    }

    $body = json_encode(array_merge(['access_key' => $accessKey], $payload), JSON_UNESCAPED_UNICODE);

    if ($body === false) {
        return false;
    }

    $curl = curl_init('https://api.web3forms.com/submit');
    $caBundle = __DIR__ . '/cacert.pem';
    $options = [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_TIMEOUT => 15,
    ];

    if (is_file($caBundle)) {
        $options[CURLOPT_CAINFO] = $caBundle;
    }

    curl_setopt_array($curl, $options);

    $raw = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($raw === false || $status >= 400) {
        return false;
    }

    $decoded = json_decode($raw, true);

    return is_array($decoded) && !empty($decoded['success']);
}

function api_sanitize(string $value, int $maxLength = 5000): string
{
    return mb_substr(trim(strip_tags($value)), 0, $maxLength);
}
