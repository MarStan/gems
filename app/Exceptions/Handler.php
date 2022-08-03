<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register()
    {
        // Order of callbacks is important.

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return ApiResponse::notFound('Not found', $this->getPayload($e), $e->getHeaders());
            }
        });

        $this->renderable(function (HttpExceptionInterface $e, Request $request) {
            if ($request->wantsJson()) {
                return ApiResponse::respondError($e->getStatusCode(), $e->getMessage(), $this->getPayload($e), $e->getHeaders());
            }
        });
    }

    protected function getPayload(Throwable $e, array $data = []): array
    {
        return array_merge(
            config('app.debug')
                ? ['exception' => get_class($e), 'trace' => $e->getTrace()]
                : [],
            $data
        );
    }

    protected function unauthenticated($request, AuthenticationException $e)
    {
        return ApiResponse::unauthenticated();
    }
}
