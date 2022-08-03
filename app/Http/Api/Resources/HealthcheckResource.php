<?php

namespace App\Http\Api\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthcheckResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request)
    {
        return [];
    }
}
