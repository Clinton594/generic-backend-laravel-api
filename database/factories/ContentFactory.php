<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;

        $contentType = array_values(config('data.contentType'));
        $status = array_values(config('data.approval'));

        return [
            'id' => Str::uuid(),
            'title' => $faker->realText(20),
            'body' => $faker->paragraph,
            'created_by' => User::all()->unique()->random()->id,
            "image" => $faker->url(),
            "url" => $faker->url(),
            "type" =>  $contentType[array_rand($contentType)],
            "status" => $status[array_rand($status)],
        ];
    }
}
