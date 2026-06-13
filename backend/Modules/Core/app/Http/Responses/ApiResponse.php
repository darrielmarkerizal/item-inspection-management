<?php

namespace Modules\Core\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    public static function created(
        mixed $data = null,
        string $message = 'Created successfully'
    ): JsonResponse {
        return self::success($data, $message, 201);
    }

    public static function error(
        string $message = 'Something went wrong',
        mixed $errors = null,
        int $statusCode = 400
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode);
    }

    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, null, 404);
    }

    public static function unprocessable(mixed $errors): JsonResponse
    {
        return self::error('Validation failed', $errors, 422);
    }

    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, 403);
    }
}