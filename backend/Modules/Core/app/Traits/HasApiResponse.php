<?php

namespace Modules\Core\Traits;

use Modules\Core\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

trait HasApiResponse
{
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        return ApiResponse::success($data, $message, $statusCode);
    }

    protected function created(mixed $data = null, string $message = 'Created successfully'): JsonResponse
    {
        return ApiResponse::created($data, $message);
    }

    protected function error(
        string $message = 'Something went wrong',
        mixed $errors = null,
        int $statusCode = 400
    ): JsonResponse {
        return ApiResponse::error($message, $errors, $statusCode);
    }

    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return ApiResponse::notFound($message);
    }
}