<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum GameRound: string
{
    use EnumToArray;

    case OneEighth = '1/8';
    case OneFourth = '1/4';
    case SemiFinal = '1/2';
    case TheFinal  = '1';
}
