<?php

namespace App\Console\Commands;

use App\Jobs\ImportCountries;
use Illuminate\Console\Command;

class ImportCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import countries to database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ImportCountries::dispatch();
    }
}
