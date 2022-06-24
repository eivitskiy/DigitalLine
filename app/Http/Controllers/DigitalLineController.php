<?php

namespace App\Http\Controllers;

use App\Enums\GameType;
use App\Helpers\GamesTree;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;

class DigitalLineController extends Controller
{
    public function index(): Factory|View|Application
    {
        $divisions         = Team::getTeamsByDivisions();
        $playOffGames      = Game::where('type', GameType::PLAYOFF)->get();
        $playOffGamesQueue = (new GamesTree())->traversal();
        $looseTeams        = collect();

        foreach ($divisions as $teams) {
            $looseTeams->push($teams->sortBy(fn($team) => $team->score)->first());
        }

        return view('tournament', [
            'divisions'         => $divisions,
            'playoffGames'      => $playOffGames,
            'playoffGamesQueue' => $playOffGamesQueue,
            'looseTeams'        => $looseTeams->sortByDesc('score'),
        ]);
    }

    public function regenerate(): Redirector|Application|RedirectResponse
    {
        Artisan::call('db:seed');

        return redirect('/');
    }
}
