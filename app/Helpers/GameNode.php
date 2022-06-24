<?php

namespace App\Helpers;

use App\Models\Game;

class GameNode
{
    public Game|null $game = null;
    public GameNode|null $left = null;
    public GameNode|null $right = null;

    public function __construct(?Game $game)
    {
        if ($game) {
            $this->game = $game;

            $childGames = Game::where('round', $this->game->round->prevRound())->get();

            $this->left  = new GameNode($childGames->shift());
            $this->right = new GameNode($childGames->shift());
        }
    }
}