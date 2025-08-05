<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\MessageBag;

trait ApiResponse
{
    /**
     * General success response.
     */
    protected function successResponse(array $data = [], string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'code'    => $status,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * General error response.
     */
    protected function errorResponse(string $message = 'Error', int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'code'    => $status,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /**
     * Response for validation error.
     */
    protected function validationErrorResponse(MessageBag|array $errors, string $message = 'Validation failed', int $status = 422): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'code'    => $status,
            'message' => $message,
            'errors'  => $errors instanceof MessageBag ? $errors->toArray() : $errors,
        ], $status);
    }

    /**
     * Response for paginated data.
     */
    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status'    => true,
            'code'      => $status,
            'message'   => $message,
            'data'      => $paginator->items(),
            'pagination'=> [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ]
        ], $status);
    }
}
