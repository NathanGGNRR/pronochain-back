<?php

namespace App\Console\Commands;

use App\Enums\Sports;
use App\Jobs\ImportGames;
use App\Traits\SportManageable;
use Illuminate\Console\Command;

class ImportGamesCommand extends Command
{
    use SportManageable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:games {sport? : the sport you want the games for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import games of specific sport to database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sport = $this->argument('sport') ?? $this->ask('Sport (Available: '.$this->getAvailableSportsToString().')');

        if ($this->sportNotAvailable($sport)) {
            $this->displayUnavailableSportMessage();

            return Command::FAILURE;
        }

        ImportGames::dispatch(Sports::from($sport));

        return Command::SUCCESS;
    }
}
