<?php

namespace App\Enums;

use App\Contracts\SoccerApi;
use App\Factories\SoccerFactory;
use App\Models\Sport;

enum Sports: string
{
    case SOCCER = 'soccer';
    public function getInterface(): string
    {
        return match ($this) {
            self::SOCCER => SoccerApi::class,
        };
    }

    public function getFactory(): string
    {
        return match ($this) {
            self::SOCCER => SoccerFactory::class,
        };
    }

    public function getModel()
    {
        return match ($this) {
            self::SOCCER => Sport::whereCode(self::SOCCER)->firstOrFail(),
        };
    }
}
