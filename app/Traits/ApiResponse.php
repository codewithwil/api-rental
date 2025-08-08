<?php
namespace App\Traits;

use Illuminate\{
    Http\JsonResponse,
    Pagination\LengthAwarePaginator,
    Support\MessageBag
};

trait ApiResponse
{
    protected function successResponse(array $data = [], string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status'  => $status,
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function errorMessage(string $message = 'Error', int $status = 400): JsonResponse
    {
        return response()->json([
            'status'  => $status,
            'success' => false,
            'message' => $message,
        ], $status);
    }

    protected function validationError(MessageBag $errors, int $status = 422): JsonResponse
    {
        return response()->json([
            'status'  => $status,
            'success' => false,
            'message' => 'Validation failed',
            'errors'  => $errors->toArray(),
        ], $status);
    }

    protected function paginatedResponse(LengthAwarePaginator $paginator, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'status'    => $status,
            'success'   => true,
            'message'   => $message,
            'data'      => $paginator->items(),
            'pagination'=> [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'from'         => $paginator->firstItem(),
                'to'           => $paginator->lastItem(),
            ]
        ], $status);
    }

    protected function custom(array $response, int $status = 200): JsonResponse
    {
        return response()->json($response, $status);
    }
}
