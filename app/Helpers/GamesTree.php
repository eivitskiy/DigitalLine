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
        $this->nodeTraversal($dummyQueue, $this->root);

        return $this->queue;
    }

    protected function nodeTraversal($dummyQueue, $node): void
    {
        if ($node->left) {
            $dummyQueue->enqueue($node->left);
        }
        if ($node->right) {
            $dummyQueue->enqueue($node->right);
        }

        if (!$dummyQueue->isEmpty()) {
            $nextNode      = $dummyQueue->dequeue();
            $this->queue[] = $nextNode;
            $this->nodeTraversal($dummyQueue, $nextNode);
        }
    }
}