<?php

namespace App\Jobs;

use App\Enums\Sports;
use App\Models\League;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportLeagues implements ShouldQueue
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
    public function handle(): void
    {
        $service = app()->make($this->sport->getInterface());
        $factory = app()->make($this->sport->getFactory());

        foreach ($service->getLeagues() as $league) {
            if ($this->leagueDoesntExists($league)) {
                $factory->createLeague($league);
            }
        }
    }

    private function leagueDoesntExists(object $league)
    {
        return League::where([
            ['sport_id', '=', $this->sport->getModel()->id],
            ['code', '=', $league->league->id],
        ])->doesntExist();
    }
}
