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
        if (!$request->hasHeader("user")) {
            throw new \Exception("Operação inválida, você precisa estar logado!");
        }

        if (!is_numeric($request->header("user"))) {
            throw new \Exception("Operação inválida, você precisa passar um id válido");
        }

        $user = Usuario::where("USUARIO_ID", $request->header("user"))->first();

        if (!$user->USUARIO_NOME) {
            throw new \Exception("Usuário inexistente!");
        }

        return $next($request);
    }
}
