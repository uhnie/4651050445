<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithTh2
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: thiếu Bearer token.',
            ], 401);
        }

        try {
            $response = Http::timeout((int) env('AUTH_SERVICE_TIMEOUT', 5))
                ->acceptJson()
                ->withToken($token)
                ->post(env('AUTH_SERVICE_URL', 'http://127.0.0.1:8000/auth'));

            if ($response->successful() && $response->json('valid') === true) {
                return $next($request);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: token không hợp lệ.',
            ], 401);
        } catch (ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication service không khả dụng.',
            ], 503);
        }
    }
}
