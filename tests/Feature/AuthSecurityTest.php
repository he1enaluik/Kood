<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_passwords_are_hashed_in_database(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('secret-password'),
        ]);

        $this->assertNotSame('secret-password', $user->fresh()->password);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    public function test_login_form_is_csrf_protected(): void
    {
        $response = $this->get(route('login'));

        $response
            ->assertOk()
            ->assertSee('name="_token"', false);
    }
}
