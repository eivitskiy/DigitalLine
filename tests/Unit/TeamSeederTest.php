<?php

namespace Tests\Unit;

use App\Models\Team;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\TeamSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function test_seeder_run(): void
    {
        Team::truncate();

        $this->assertDatabaseCount((new Team)->getTable(), 0);

        (new DatabaseSeeder())->call(TeamSeeder::class);

        $this->assertDatabaseCount((new Team)->getTable(), 10);
    }
}
