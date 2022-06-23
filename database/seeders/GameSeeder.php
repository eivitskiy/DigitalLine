<?php

namespace Database\Seeders;

use App\Enums\Division;
use App\Enums\GameRound;
use App\Enums\GameType;
use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class GameSeeder extends Seeder
{
    protected static function addGame(Team $teamA, Team $teamB, Carbon $date, GameType $type, ?GameRound $round): void
    {
        if ($teamA->id === $teamB->id) {
            return;
        }

        if ($teamA->gamesWithTeam($teamB, $type)->exists()) {
            return;
        }

        Game::factory()->state([
            'participant_a' => $teamA->id,
            'participant_b' => $teamB->id,
            'type'          => $type,
            'round'         => $round,
            'date'          => $date->addDay()->format('Y-m-d'),
        ])->create();
    }

    /**
     * @throws Exception
     */
    protected static function generateGroupGames(): void
    {
        $divisions = Team::getTeamsByDivisions();

        $date = now()->subDays(random_int(365, 730));

        foreach ($divisions as $teams) {
            /** @var Team $teamA */
            foreach ($teams as $teamA) {
                /** @var Team $teamB */
                foreach ($teams as $teamB) {
                    self::addGame($teamA, $teamB, $date, GameType::GROUP, null);
                }
            }
        }
    }

    protected static function getTeamsByWinners($limit): array
    {
        $divisions = Team::getTeamsByDivisions();

        // построить дерево

        // победители по очкам
        foreach ($divisions as &$teams) {
            /** @var Collection $teams */
            $teams = $teams->sortByDesc(fn($team) => $team->score)->take($limit);
        }

        return $divisions;
    }

    /**
     * @throws Exception
     */
    protected static function generatePlayoffGames(): void
    {
        //TODO: доделать

        $round = 4;

        $divisions = Team::getTeamsByDivisions();

        $date = now()->subDays(random_int(180, 365));

        while(1 !== $round) {
            $limit = $round;

            /** @noinspection AlterInForeachInspection */
            foreach ($divisions as &$teams) {
                /** @var Collection $teams */
                $teams = $teams->sortByDesc(fn($team) => $team->score)->take($limit);
            }

            $i = 1;
            $j = $limit;

            while ($limit > 0) {
                $teamA = Arr::first($divisions)->take($i)->last();
                $teamB = Arr::last($divisions)->take($j)->last();

                self::addGame($teamA, $teamB, $date, GameType::PLAYOFF, GameRound::from("1/$round"));

                $i++;
                $j--;
                $limit--;
            }

            $round /= 2;
        }
    }

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        DB::transaction(static function () {
            Game::truncate();

            self::generateGroupGames();
            self::generatePlayoffGames();
        });
    }
}
