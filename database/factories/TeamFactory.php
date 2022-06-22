<?php

namespace Database\Factories;

use App\Enums\Division;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class TeamFactory extends Factory
{
    #[ArrayShape(['name' => "string", 'division' => "mixed"])] public function definition(): array
    {
        return [
            'name'     => $this->faker->company,
            'division' => Arr::random(Division::values()),
        ];
    }
}
