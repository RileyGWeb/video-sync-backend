<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class VerifyTwitchJwt
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Missing or invalid Authorization header'], 401);
        }

        $jwt = substr($authHeader, 7);
        $secret = config('services.twitch.extension_secret'); // add this to your config/services.php

        try {
            $payload = JWT::decode($jwt, new Key($secret, 'HS256'));
            $request->merge(['twitch_user' => (array) $payload]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'JWT validation failed: ' . $e->getMessage()], 401);
        }

        return $next($request);
    }
}
