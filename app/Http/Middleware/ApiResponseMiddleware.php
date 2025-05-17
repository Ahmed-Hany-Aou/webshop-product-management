<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Process the request
        $response = $next($request);

        // Only transform API responses
        if (!$request->is('api/*') || !($response instanceof JsonResponse)) {
            return $response;
        }

        // Get the original content
        $content = $response->getContent();
        $data = json_decode($content, true);
        
        // If response is already in our standard format, return it as is
        if (isset($data['status_code']) && isset($data['message']) && array_key_exists('result', $data)) {
            return $response;
        }

        // Get status code
        $statusCode = $response->getStatusCode();

        // Determine if this is an error response
        $isError = $statusCode >= 400;

        // Create standardized response structure
        $standardizedResponse = [
            'status_code' => $statusCode,
            'message' => $this->getDefaultMessage($statusCode),
            'result' => $isError ? null : $data
        ];

        // If it's an error and we have error details
        if ($isError && !empty($data)) {
            if (isset($data['message'])) {
                $standardizedResponse['message'] = $data['message'];
            }
            
            if (isset($data['errors'])) {
                $standardizedResponse['result'] = ['errors' => $data['errors']];
            }
        }

        // Return the standardized response
        return response()->json($standardizedResponse, $statusCode);
    }

    /**
     * Get default message for HTTP status code
     *
     * @param int $statusCode
     * @return string
     */
    private function getDefaultMessage(int $statusCode): string
    {
        $messages = [
            200 => 'Operation successful',
            201 => 'Resource created successfully',
            204 => 'Resource deleted successfully',
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Resource not found',
            422 => 'Validation failed',
            429 => 'Too many requests',
            500 => 'Server error',
        ];

        return $messages[$statusCode] ?? 'Unknown status';
    }
}
