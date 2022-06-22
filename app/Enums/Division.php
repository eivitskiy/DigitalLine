<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum Division: string
{
    case A = 'a';
    case B = 'b';

    public static function list(): Collection
    {
        return collect(self::cases())
            ->map(static fn($division) => $division->value);
    }
}