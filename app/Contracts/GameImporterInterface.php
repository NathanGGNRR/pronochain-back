<?php

namespace App\Contracts;

use App\Enums\Sports;

interface GameImporterInterface
{
    public function handle(Sports $sport): void;
}
