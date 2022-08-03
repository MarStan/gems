<?php

namespace App\Http\Api\Actions\Healthcheck;


use App\Http\Api\Actions\Action;
use App\Http\Requests\HealthcheckRequest;
use App\Http\Responses\ApiResponse;

class Healthcheck extends Action
{
    public function __invoke(HealthcheckRequest $request): ApiResponse
    {
        return ApiResponse::success([
            'id' => 1,
        ]);
    }
}
