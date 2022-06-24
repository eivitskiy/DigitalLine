<?php

namespace Tests\Feature;

use Database\Seeders\GameSeeder;
use Database\Seeders\TeamSeeder;
use Tests\TestCase;

class WebTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(TeamSeeder::class);
        $this->seed(GameSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_regenerate_a_redirect_response(): void
    {
        $response = $this->get(route('regenerate'));

        $response->assertStatus(302);
    }
}
