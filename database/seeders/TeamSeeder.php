<?php

namespace Database\Seeders;

use App\Enums\Division;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Throwable;

class TeamSeeder extends Seeder
{
    /**
     * @throws Throwable
     */
    public function run(): void
    {
        DB::transaction(static function () {
            Team::truncate();

            $teamsCount = 5;

            foreach(Division::cases() as $division) {
                Team::factory($teamsCount)->state([
                    'division' => $division,
                ])->create();
            }
        });
    }
}
