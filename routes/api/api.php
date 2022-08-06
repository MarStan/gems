<?php

use App\Http\Api\Actions\Healthcheck\Healthcheck;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('api/v1')->group(function (Router $router) {
    $router->get('healthcheck', Healthcheck::class);
});
