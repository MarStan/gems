<?php

namespace App\Providers;

use App\Services\Api\CalendarApiClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

/**
 * @codeCoverageIgnore
 */
class CalendarApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->registerClient();
    }

    public function provides()
    {
        return [
            CalendarApiClient::class,
        ];
    }

    protected function registerClient(): void
    {
        $this->app->singleton(CalendarApiClient::class, function () {
            /** @var \Illuminate\Contracts\Config\Repository */
            $config = $this->app->make('config');
            /** @var array */
            $operationRulesConfig = $config->get('calendar-api-client');
            /** @var string */
            $defaultEnv = $operationRulesConfig['env'] ?? 'local';
            /** @var array */
            $configValues = $operationRulesConfig['environments'][$defaultEnv] ?? [];

            if (! $configValues) {
                throw new RuntimeException("No calendar api configuration found for [{$defaultEnv}] environment.");
            }

            /** @var \Illuminate\Http\Client\Factory */
            $client = $this->app->make(Factory::class);

            return new CalendarApiClient($client, $configValues);
        });
    }
}
