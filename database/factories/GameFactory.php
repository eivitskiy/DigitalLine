<?php

namespace Database\Factories;

use App\Enums\GameRound;
use App\Enums\GameType;
use App\Models\Team;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;

class GameFactory extends Factory
{
    /**
     * @throws Exception
     */
    #[ArrayShape([
        'participant_a' => "int",
        'participant_b' => "int",
        'score_a'       => "int",
        'score_b'       => "int",
        'date'          => "string",
        'type'          => "array|mixed",
        'round'         => "array|mixed"
    ])] public function definition(): array
    {
        /** @var Team $teamA */
        $teamA = Team::inRandomOrder()->first();
        /** @var Team $teamB */
        $teamB = Team::where('id', '!=', $teamA->id)
            ->inRandomOrder()->first();

        $game = [
            'participant_a' => $teamA->id,
            'participant_b' => $teamB->id,
            'score_a'       => random_int(0, 5),
            'score_b'       => random_int(0, 5),
            'date'          => $this->faker->date,
            'type'          => Arr::random(GameType::cases()),
        ];

        if (GameType::PLAYOFF === $game['type']) {
            $game['round'] = Arr::random(GameRound::cases());
        }

        return $game;
    }
}
