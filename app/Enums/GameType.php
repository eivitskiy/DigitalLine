<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum GameType: string
{
    use EnumToArray;

    case GROUP = 'group';
    case PLAYOFF = 'playoff';
}