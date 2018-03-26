<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    // Lo que hace es agregar una cabecera personalizada
    // Este es un after-middleware, porque es un middleware que se construye despues de haber construido la respuesta

    // Si quisieramos que fuera un before-middleware tendria que haber ejecutado todo antes de $response = next($request)

    
    public function handle($request, Closure $next, $header = 'X-Name')
    {
        $response = $next($request);
        
        $response->headers->set($header, config('app.name'));

        return $response;
    }
}
