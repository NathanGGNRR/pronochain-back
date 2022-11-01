<?php

namespace App\Jobs;

use App\Contracts\CountryApi;
use App\Models\Country;
use App\Services\CountryManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCountries implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(CountryApi $countryApi, CountryManager $countryManager): void
    {
        foreach ($countryApi->getCountries() as $country) {
            Country::updateOrCreate(
                ['code' => $countryManager->getCountryCode($country)],
                ['flag_url' => $country->flag]
            );
        }
    }
}
