<?php

namespace App\Jobs;

use App\Contracts\GameImporterInterface;
use App\Contracts\OddsImporterInterface;
use App\Enums\Sports;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportGames implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Sports $sport;

    /**
     * Create a new job instance.
     */
    public function __construct(Sports $sport)
    {
        $this->sport = $sport;
    }

    /**
     * Execute the job.
     */
    public function handle(GameImporterInterface $gameImporter, OddsImporterInterface $oddsImporter): void
    {
        $gameImporter->handle($this->sport);
        $oddsImporter->handle($this->sport);
    }
}
