<?php

namespace App\Http\Middleware;

use Closure;

class VerifyKey
{
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('x-key-ocr');

        if (!$apiKey || $apiKey !== 'ajw6ukbu3wsm4fmcscfft98pffxrnufz') {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return $next($request);
    }
}