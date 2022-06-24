<?php

namespace App\Helpers;

use App\Enums\GameRound;
use App\Models\Game;
use SplQueue;

class GamesTree
{
    protected GameNode|null $root = null;
    protected array $queue = [];

    public function __construct()
    {
        $theFinalGame = Game::where('round', GameRound::TheFinal)->first();

        $this->root = new GameNode($theFinalGame);
    }

    public function traversal(): array
    {
        $dummyQueue = new SplQueue();
        $dummyQueue->enqueue($this->root);
        $this->nodeTraversal($dummyQueue);

        return $this->queue;
    }

    protected function nodeTraversal(SplQueue $dummyQueue): void
    {
        if (!$dummyQueue->isEmpty()) {

            $node          = $dummyQueue->dequeue();
            $this->queue[] = $node;

            if ($node->left) {
                $dummyQueue->enqueue($node->left);
            }
            if ($node->right) {
                $dummyQueue->enqueue($node->right);
            }

            $this->nodeTraversal($dummyQueue);
        }
    }
}