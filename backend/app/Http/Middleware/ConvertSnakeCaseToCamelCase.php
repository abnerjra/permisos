<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ConvertSnakeCaseToCamelCase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public const CASE_SNAKE = 'snake';
    public const CASE_CAMEL = 'camel';

    public function handle(Request $request, Closure $next)
    {
        $request->replace(
            $this->convertKeysToCase(
                self::CASE_CAMEL,
                $request->all()
            )
        );
        $response = $next($request);
        if ($response instanceof JsonResponse) {
            $response->setData(
                $this->convertKeysToCase(
                    self::CASE_SNAKE,
                    json_decode($response->content(), true)
                )
            );
        }
        return $response;
    }
    
    private function convertKeysToCase(string $case, $data)
    {
        if (!is_array($data)) {
            return $data;
        }

        $array = [];

        foreach ($data as $key => $value) {
            $array[Str::{$case}($key)] = is_array($value)
                ? $this->convertKeysToCase($case, $value)
                : $value;
        }

        return $array;
    }
}
