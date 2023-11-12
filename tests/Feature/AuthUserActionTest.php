<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthUserActionTest extends TestCase
{
    public function testCanAuthUser(): void
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->post('/api/auth', $data)
            ->assertSuccessful();
    }

    public function testCantAuthUserWithInvalidData()
    {
        $user = User::factory()->create();
        $data = [
            'email' => $user->email,
            'password' => 'invalid-password',
        ];

        $this->post('/api/auth', $data)
            ->assertStatus(401);
    }
}
