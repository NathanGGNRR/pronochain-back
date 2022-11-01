<?php

namespace App\Console\Commands;

use App\Enums\Sports;
use App\Jobs\ImportLeagues;
use App\Traits\SportManageable;
use Illuminate\Console\Command;

class ImportLeaguesCommand extends Command
{
    use SportManageable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:leagues {sport? : the sport you want the leagues for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import leagues of specific sport to database';

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

        ImportLeagues::dispatch(Sports::from($sport));

        return Command::SUCCESS;
    }
}
