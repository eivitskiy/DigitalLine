<?php

use App\Enums\GameType;
use App\Helpers\GamesTree;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', static function () {
    return view('tournament', [
        'divisions'         => Team::getTeamsByDivisions(),
        'playoffGames'      => Game::where('type', GameType::PLAYOFF)->get(),
        'playoffGamesQueue' => (new GamesTree)->traversal(),
    ]);
});

Route::get('regenerate', static function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed');

    return redirect('/');
})->name('regenerate');
