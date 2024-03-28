<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResopnse;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', IlluminateResponse::HTTP_OK, $headers);
        }

        $response = $next($request);

        if($response instanceof IlluminateResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }

        }elseif ($response instanceof SymfonyResopnse) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
        } else{
            foreach($headers as $key => $value) {
                $response->header($key, $value);
            }
        }

        return $response;
    }
}
