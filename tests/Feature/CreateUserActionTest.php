<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCanCreateUser(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/api/users', $data)
            ->assertSuccessful();
    }

    public function testCantCreateUserWithDuplicatedEmail(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => $this->faker->name(),
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/api/users', $data)
            ->assertStatus(422);
    }

    public function testCantCreateUserWithoutPasswordConfirmation()
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'password' => 'password',
        ];

        $this->post('/api/users', $data)
            ->assertStatus(422);
    }
}
