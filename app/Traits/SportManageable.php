<?php

namespace App\Traits;

use App\Enums\Sports;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait SportManageable
{
    public function displayUnavailableSportMessage(): void
    {
        $this->error('The selected sport is not yet supported. Available sports : '.$this->getAvailableSportsToString());
    }

    public function getAvailableSportsToString(): string
    {
        return implode(', ', Arr::pluck(Sports::cases(), 'value'));
    }

    public function sportNotAvailable(string $sport_name): bool
    {
        return !Sports::tryFrom(Str::lower($sport_name));
    }
}
