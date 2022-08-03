<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse extends JsonResponse
{
    public static function success(JsonResource | array $data = [], array $headers = []): self
    {
        if ($data instanceof JsonResource) {
            $data = $data->resolve(request());
        }

        return static::respond(200, $data, $headers);
    }

    public static function paginate(LengthAwarePaginator $paginator, array $headers = []): self
    {
        return static::respond(200, [
            'current_page' => $paginator->currentPage(),
            'data' => $paginator->items(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ], $headers);
    }

    public static function paginateResource(LengthAwarePaginator $paginator, string $resource, array $headers = []): self
    {
        return static::respond(200, [
            'current_page' => $paginator->currentPage(),
            'data' => $resource::collection($paginator->getCollection())->toArray(request()),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ], $headers);
    }

    public static function noContent(array $headers = []): self
    {
        return static::respond(204, [], $headers);
    }

    public static function badRequest(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(400, $message, $data, $headers);
    }

    public static function unauthenticated(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(401, $message, $data, $headers);
    }

    public static function unauthorized(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(403, $message, $data, $headers);
    }

    public static function notFound(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(404, $message, $data, $headers);
    }

    public static function methodNotAllowed(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(405, $message, $data, $headers);
    }

    public static function unprocessableEntity(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(422, $message, $data, $headers);
    }

    public static function tooManyRequests(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(429, $message, $data, $headers);
    }

    public static function serverError(?string $message = null, array $data = [], array $headers = []): self
    {
        return static::respondError(500, $message, $data, $headers);
    }

    public static function respond(int $status, array $data = [], array $headers = []): self
    {
        return new static($data, $status, $headers);
    }

    public static function respondError(int $status, ?string $message = null, array $data = [], array $headers = []): self
    {
        $data['message'] = empty($message)
            ? JsonResponse::$statusTexts[$status]
            : $message;

        return static::respond($status, $data, $headers);
    }
}
