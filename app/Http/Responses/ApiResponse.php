<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class ApiResponse
 * 
 * Standardizes API responses across the application
 */
class ApiResponse
{
    /**
     * Create a standardized success response
     *
     * @param mixed $data The data to be returned
     * @param string $message The success message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Operation successful', int $statusCode = 200): JsonResponse
    {
        $response = [
            'status_code' => $statusCode,
            'message' => $message,
            'result' => $data
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Create a standardized error response
     *
     * @param string $message The error message
     * @param int $statusCode HTTP status code
     * @param mixed $errors Additional error details
     * @return JsonResponse
     */
    public static function error(string $message = 'An error occurred', int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'status_code' => $statusCode,
            'message' => $message,
        ];

        if ($errors) {
            $response['result'] = ['errors' => $errors];
        } else {
            $response['result'] = null;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Create a standardized paginated response
     *
     * @param LengthAwarePaginator $paginator The paginator instance
     * @param string $message The success message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public static function paginate(LengthAwarePaginator $paginator, string $message = 'Data retrieved successfully', int $statusCode = 200): JsonResponse
    {
        // Extract items from paginator
        $items = $paginator->items();
        
        // Build pagination metadata
        $meta = [
            'page' => $paginator->currentPage(),
            'take' => $paginator->perPage(),
            'items_count' => count($items),
            'total_items_count' => $paginator->total(),
            'page_count' => $paginator->lastPage(),
            'has_previous_page' => $paginator->currentPage() > 1,
            'has_next_page' => $paginator->hasMorePages()
        ];

        // Build the result structure
        $result = [
            'items' => $items,
            'meta' => $meta
        ];

        // Return standardized response
        return self::success($result, $message, $statusCode);
    }

    /**
     * Create a standardized collection response
     * 
     * @param Collection $collection The collection to be returned
     * @param string $message The success message
     * @param int $statusCode HTTP status code
     * @return JsonResponse
     */
    public static function collection(Collection $collection, string $message = 'Data retrieved successfully', int $statusCode = 200): JsonResponse
    {
        $result = [
            'items' => $collection->values()->all(),
            'meta' => null
        ];

        return self::success($result, $message, $statusCode);
    }
}
