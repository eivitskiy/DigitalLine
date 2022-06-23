<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum Division: string
{
    use EnumToArray;

    case A = 'a';
    case B = 'b';
}