<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => 1,
            'first_name' => "Super",
            'last_name' => "Admin",
            'type' => config('data.userType.admin'),
            'email' =>  'support@defistake.finance',
            "user_name" => 'superadmin',
            "image" => str_replace('/api', '', asset("/images/logo/favicon.ico")),
            "status" => config('data.userStatus.active'),
            'email_verified_at' => now(),
            'password' => Hash::make("Password@1234_?"),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
