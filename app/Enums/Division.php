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
        return [
            self::A->name => Team::whereDivision(self::A)->get(),
            self::B->name => Team::whereDivision(self::B)->get(),
        ];
    }
}