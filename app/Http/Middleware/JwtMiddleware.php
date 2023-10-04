<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;
use JWTAuth;
use Exception;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

               return Response::failure('Unauthorized', ['message' => 'Token is invalid'],401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {

                return Response::failure('Unauthorized', ['message' => 'Token has expired'],401);
            } else {

                return Response::failure('Unauthorized', ['message' => 'Authorization token not found'],401);
            }
        }

        return $next($request);
    }
}
