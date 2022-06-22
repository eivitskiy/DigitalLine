<?php

namespace Database\Seeders;

use App\Enums\Division;
use App\Enums\GameType;
use App\Models\Game;
use App\Models\Team;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Throwable;

class GameSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    protected static function generateGroupGames(): void
    {
        $divisions = Division::teams();

        $date = now()->subDays(random_int(365, 730));

        foreach ($divisions as $teams) {
            /** @var Team $teamA */
            foreach ($teams as $teamA) {
                /** @var Team $teamB */
                foreach ($teams as $teamB) {
                    if ($teamA->id === $teamB->id) {
                        continue;
                    }

                    if ($teamA->gamesWithTeam($teamB)->exists()) {
                        continue;
                    }

                    Game::factory()->state([
                        'participant_a' => $teamA->id,
                        'participant_b' => $teamB->id,
                        'type'          => GameType::GROUP,
                        'date'          => $date->addDay(),
                    ])->create();
                }
            }
        }
    }

    protected static function generatePlayoffGames(): void
    {
        //TODO: реализовать
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
