<?php

namespace App\Parsers;

use App\Models\Game;

class GameParser
{
    private Game $game;

    public function __construct()
    {
        $this->game = new Game();
    }

    public function setCode(string $code): self
    {
        $this->game->code = $code;

        return $this;
    }

    public function setDate(string $date): self
    {
        $this->game->date = $date;

        return $this;
    }

    public function setReferee(?string $referee): self
    {
        $this->game->referee = $referee;

        return $this;
    }

    public function setUpdatedAt(string $updated_at): self
    {
        $this->game->updated_at = $updated_at;

        return $this;
    }

    public function build(): Game
    {
        return $this->game;
    }
}
