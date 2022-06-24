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
    protected Carbon $date;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->date = now()->subDays(random_int(180, 365));
    }

    /**
     * @throws Exception
     */
    protected function addGame(Team $teamA, Team $teamB, GameType $type, ?GameRound $round): ?Game
    {
        if ($teamA->id === $teamB->id) {
            return null;
        }

        if ($teamA->gamesWithTeam($teamB, $type)->exists()) {
            return null;
        }

        $state = [
            'participant_a' => $teamA->id,
            'participant_b' => $teamB->id,
            'type'          => $type,
            'round'         => $round,
            'date'          => $this->date->addDay()->format('Y-m-d'),
        ];

        if (GameType::PLAYOFF === $type) {
            $state['score_a'] = random_int(0, 10);
            do {
                $state['score_b'] = random_int(0, 10);
            } while ($state['score_a'] === $state['score_b']);
        }

        return Game::factory()->state($state)->create();
    }

    /**
     * @throws Exception
     */
    protected function generateGroupGames(): void
    {
        $divisions = Team::getTeamsByDivisions();

        foreach ($divisions as $teams) {
            /** @var Team $teamA */
            foreach ($teams as $teamA) {
                /** @var Team $teamB */
                foreach ($teams as $teamB) {
                    $this->addGame($teamA, $teamB, GameType::GROUP, null);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function generateOneFourthGames(): \Illuminate\Support\Collection
    {
        $divisions = Team::getTeamsByDivisions();
        $limit     = 4;

        $games = collect();

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

            if ($game = $this->addGame($teamA, $teamB, GameType::PLAYOFF, GameRound::OneFourth)) {
                $games->push($game);
            }

            $i++;
            $j--;
            $limit--;
        }

        return $games;
    }

    /**
     * @throws Exception
     */
    protected function generatePlayoffGames(GameRound $round): \Illuminate\Support\Collection
    {
        if (null === $round->prevRound()) {
            $prevGames = $this->generateOneFourthGames();
        } else {
            $prevGames = $this->generatePlayoffGames($round->prevRound());
        }

        $games = collect();

        $prevGames->sliding(2,2)->eachSpread(function ($previous, $current) use ($games) {
            /**
             * @var Game $previous
             * @var Game $current
             */
            $teamA = $previous->winner;
            $teamB = $current->winner;

            $round = $current->round->nextRound();

            if ($game = $this->addGame($teamA, $teamB, GameType::PLAYOFF, $round)) {
                $games->push($game);
            }
        });

        return $games;
    }

    /**
     * @throws Throwable
     */
    public function run(): void
    {
        DB::transaction(function () {
            Game::truncate();

            $this->generateGroupGames();
            $this->generatePlayoffGames(GameRound::TheFinal);
        });
    }
}
