<?php

namespace Sibas\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{

    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        
        if (!$token)
            return response()->json(['error' => 'Token no proporcionado'], 400);
        
        //$token = str_replace('Bearer ', '', $token);
        
        try {
            $decoded = JWT::decode($token, new Key('3001', 'HS256'));

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token invalido'], 401);
        }
    }
}
