<?php

namespace App\Enums;

use App\Models\Team;
use App\Traits\EnumToArray;

enum Division: string
{
    use EnumToArray;

    case A = 'a';
    case B = 'b';

    public static function teams(): array
    {
        $teams = [];

        foreach(self::cases() as $division) {
            $teams[$division->name] = Team::whereDivision($division)->get();
        }

        return $teams;
    }
}