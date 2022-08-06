<?php

namespace App\Providers;

use App\Services\Api\PersonDataApiClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

/**
 * @codeCoverageIgnore
 */
class PersonDataApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->registerClient();
    }

    public function provides()
    {
        return [
            PersonDataApiClient::class,
        ];
    }

    protected function registerClient(): void
    {
        $this->app->singleton(PersonDataApiClient::class, function () {
            /** @var \Illuminate\Contracts\Config\Repository */
            $config = $this->app->make('config');
            /** @var array */
            $personDataApiConfig = $config->get('person-data-api-client');
            /** @var string */
            $defaultEnv = $personDataApiConfig['env'] ?? 'local';
            /** @var array */
            $configValues = $personDataApiConfig['environments'][$defaultEnv] ?? [];

            if (! $configValues) {
                throw new RuntimeException("No person data api configuration found for [{$defaultEnv}] environment.");
            }

            /** @var \Illuminate\Http\Client\Factory */
            $client = $this->app->make(Factory::class);

            return new PersonDataApiClient($client, $configValues);
        });
    }
}
