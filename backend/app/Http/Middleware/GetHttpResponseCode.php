<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GetHttpResponseCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the response is an instance of BinaryFileResponse
        if (!$response instanceof BinaryFileResponse) {
            // Check if the status() method exists in BinaryFileResponse
            if (method_exists($response, 'status')) {
                // Get the HTTP code of the response
                $statusCode = $response->status();
                if ($statusCode === 401) {
                    return response()->json([
                        "message" => "Sesión terminada",
                        "severity" => "error",
                        "results" => []
                    ], 401);
                }
            }
        }

        return $response;
    }
}
