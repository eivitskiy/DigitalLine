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

            Team::factory(10)->state([
                'division' => Division::A,
            ])->create();

            Team::factory(10)->state([
                'division' => Division::B,
            ])->create();
        });
    }
}
