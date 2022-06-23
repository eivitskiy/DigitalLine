<?php

namespace Database\Factories;

use App\Enums\Division;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class TeamFactory extends Factory
{
    #[ArrayShape(['name' => "string", 'division' => "mixed"])] public function definition(): array
    {
        do {
            $name = $this->faker->company;
        } while (Str::length($name) > 32);

        return [
            'name'     => $name,
            'division' => Arr::random(Division::values()),
        ];
    }
}
