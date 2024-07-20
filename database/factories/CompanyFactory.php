<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id" => "1",
            "name" => "Defistake",
            "logo_ref" => json_encode([
                ["src" => "/images/logo/logo.png"],
                ["src" => "/images/logo/logo-light.png"],
                ["src" => "/images/logo/favicon.ico"]
            ]),
            "website" => config("app.frontend"),
            "email" => "support@defistake.finance",
            "phone" => "-",
            "email_channel" => "mailtrap",
            "notifier" => "defistake@inmailer.email",
        ];
    }
}
