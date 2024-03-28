<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\TokenRepository;

class CheckExpireTimeToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user(); // We get the authenticated user
        if (!is_null($user)) { // We validate that the user is authenticated
            $accessToken = $user->token(); // We obtain the user's access token
            if ($accessToken) { // Validate that a token exists
                $fechaExpiracion = Carbon::parse($accessToken->expires_at);

                if ($fechaExpiracion->isPast()) { // We validate that the token is not less than the current date
                    $tokenRepository = app(TokenRepository::class);
                    RefreshToken::where('access_token_id', $accessToken->id)->update(['revoked' => 1]);  // Refresh, if it exists, it is disabled
                    $tokenRepository->revokeAccessToken($accessToken->id); // disable its use...
                    return response()->json([
                        "message" => "Sesión terminada",
                        "severity" => "error",
                        "results" => []
                    ], 401);
                }
            }
        }
        return $next($request);
    }
}
