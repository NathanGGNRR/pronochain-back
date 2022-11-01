<?php

namespace App\Enums;

use App\Models\OddType;

enum OddTypes: string
{
    case HOME_WINNER = 'home_winner';

    case AWAY_WINNER = 'away_winner';

    case DRAW = 'draw';
    public function getModel()
    {
        return match ($this) {
            self::HOME_WINNER => OddType::whereCode(self::HOME_WINNER)->firstOrFail(),
            self::AWAY_WINNER => OddType::whereCode(self::AWAY_WINNER)->firstOrFail(),
            self::DRAW => OddType::whereCode(self::DRAW)->firstOrFail(),
        };
    }
}
