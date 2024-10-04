<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header("user", null)) {
            throw new \Exception("Operação inválida, você precisa estar logado");
        }

        if (!is_numeric($request->header("user"))) {
            throw new \Exception("Operação inválida, você precisa estar logado");
        }

        if (Usuario::where("USUARIO_ID", $request->header("user"))->count() == 0) {
            throw new \Exception("Operação inválida, usuário inexistente");
        }

        return $next($request);
    }
}
