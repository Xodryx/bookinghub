<?php

namespace Tests\Feature\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_business_can_register(): void
    {
        $response = $this->postJson('/register', [
            'business_name' => 'Acme Studio',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('email', 'john@example.com')
            ->assertJsonPath('tenant.name', 'Acme Studio');

        $this->assertDatabaseHas('tenants', ['name' => 'Acme Studio']);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_registration_requires_valid_data(): void
    {
        $this->postJson('/register', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['business_name', 'name', 'email', 'password']);
    }

    public function test_a_user_can_login_with_correct_credentials(): void
    {
        $this->makeUser('jane@example.com', 'secret123');

        $this->postJson('/login', [
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ])->assertOk()->assertJsonPath('email', 'jane@example.com');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->makeUser('jane@example.com', 'secret123');

        $this->postJson('/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ])->assertStatus(422);
    }

    public function test_authenticated_user_can_fetch_their_profile(): void
    {
        $user = $this->makeUser('jane@example.com', 'secret123');

        $this->actingAs($user)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('email', 'jane@example.com');
    }

    public function test_guest_cannot_fetch_profile(): void
    {
        $this->getJson('/api/user')->assertUnauthorized();
    }

    public function test_an_authenticated_user_can_log_out(): void
    {
        $user = $this->makeUser('jane@example.com', 'secret123');

        $this->actingAs($user)
            ->postJson('/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logged out');
    }

    private function makeUser(string $email, string $password): User
    {
        $tenant = Tenant::create(['name' => 'Acme Studio']);

        return $tenant->users()->create([
            'name' => 'Jane Doe',
            'email' => $email,
            'password' => $password,
        ]);
    }
}
