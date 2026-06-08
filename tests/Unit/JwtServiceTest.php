<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JwtServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_issue_and_decode_token_for_user(): void
    {
        $user = User::factory()->admin()->create();
        $jwt = app(JwtService::class);

        $token = $jwt->issue($user);
        $resolved = $jwt->userFromToken($token);

        $this->assertNotNull($resolved);
        $this->assertSame($user->id, $resolved->id);
        $this->assertTrue($resolved->is_admin);
    }
}
