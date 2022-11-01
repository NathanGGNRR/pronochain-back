<?php

namespace App\Providers;

use App\Contracts\CountryApi;
use App\Contracts\GameImporterInterface;
use App\Contracts\OddsImporterInterface;
use App\Contracts\Signature;
use App\Contracts\SoccerApi;
use App\Contracts\Token;
use App\External\ApiFootball\Services\ApiFootballGameImporter;
use App\External\ApiFootball\Services\FootballService;
use App\Services\EthereumSignature;
use App\Services\JsonWebToken;
use App\Services\OddsImporter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Token::class, JsonWebToken::class);
        $this->app->singleton(Signature::class, EthereumSignature::class);
        $this->app->singleton(CountryApi::class, FootballService::class);
        $this->app->singleton(SoccerApi::class, FootballService::class);
        $this->app->singleton(GameImporterInterface::class, ApiFootballGameImporter::class);
        $this->app->singleton(OddsImporterInterface::class, OddsImporter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
