<?php

namespace App\Contracts;

use App\Enums\Sports;

interface OddsImporterInterface
{
    public function handle(Sports $sport): void;
}
