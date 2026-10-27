<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedCustom
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'message' => 'The email has not been verified.',
                'error_code' => 'EMAIL_NOT_VERIFIED'
            ], 403); 
        }

        return $next($request);
    }
}