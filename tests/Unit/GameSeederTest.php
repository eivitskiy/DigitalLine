<?php

namespace Tests\Unit;

use App\Models\Game;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\GameSeeder;
use Database\Seeders\TeamSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function test_seeder_run(): void
    {
        (new DatabaseSeeder())->call(TeamSeeder::class);

        Game::truncate();

        $this->assertDatabaseCount((new Game)->getTable(), 0);

        (new DatabaseSeeder())->call(GameSeeder::class);

        $this->assertDatabaseCount((new Game)->getTable(), 27);
    }
}
