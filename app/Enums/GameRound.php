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

    public function prevRound(): ?GameRound
    {
        return match ($this) {
            self::TheFinal => self::SemiFinal,
            self::SemiFinal => self::OneFourth,
            self::OneFourth => self::OneEighth,
            default => null,
        };
    }

    public function nextRound(): ?GameRound
    {
        return match ($this) {
            self::OneEighth => self::OneFourth,
            self::OneFourth => self::SemiFinal,
            self::SemiFinal => self::TheFinal,
            default => null,
        };
    }
}
