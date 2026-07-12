<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthService
{
    /**
     * Register a new business (tenant) together with its first user.
     *
     * Wrapped in a transaction so we never end up with a tenant that has
     * no user (or vice versa).
     *
     * @param  array{business_name: string, name: string, email: string, password: string}  $data
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $tenant = Tenant::create(['name' => $data['business_name']]);

            // Creating through the relationship sets tenant_id automatically,
            // so it can never be injected from user input. The password is
            // hashed by the User model's 'password' => 'hashed' cast.
            return $tenant->users()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        });
    }
}
