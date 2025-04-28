<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY'); // Obtener API Key del header

        $validApiKey = config('app.api_key'); // Obtener API Key desde .env

        if (!$apiKey || $apiKey !== $validApiKey) {
            return response()->json([
                'status' => 'error',
                'codigo_http' => 401,
                'respuesta' => null,
                'error' => 'Acceso no autorizado',
                'api_key_recibida' => $apiKey // Opcional, solo para depuración
            ], 401);
        }
        return $next($request);
    }
}
