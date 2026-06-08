<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class JwtService
{
    public function issue(User $user): string
    {
        $now = time();
        $ttl = (int) config('jwt.ttl', 60);

        $payload = [
            'iss' => config('app.url'),
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + ($ttl * 60),
            'sub' => $user->id,
            'email' => $user->email,
            'role' => $user->is_admin ? 'admin' : 'user',
        ];

        return JWT::encode($payload, $this->secret(), config('jwt.algo', 'HS256'));
    }

    public function userFromToken(string $token): ?User
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret(), config('jwt.algo', 'HS256')));
            $userId = $decoded->sub ?? null;

            return $userId ? User::query()->find($userId) : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function secret(): string
    {
        $secret = (string) config('jwt.secret');

        if ($secret === '') {
            throw new \RuntimeException('JWT_SECRET is not configured.');
        }

        return $secret;
    }

    public static function generateSecret(): string
    {
        return base64_encode(Str::random(64));
    }
}
