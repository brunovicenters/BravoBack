<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileShowResource;
use App\Http\Resources\RegisterResource;
use App\Models\Usuario;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Throw_;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        return new RegisterResource($request);
    }

    public function show(Request $request, int $id)
    {
        if (!$id) {
            throw new \Exception("Para mostrar o perfil, informe o ID do usuário");
        }

        if (!is_numeric($id)) {
            throw new \Exception("ID inválido");
        }

        $user = Usuario::where("USUARIO_ID", $id)->first();

        if ($user != null && $user->USUARIO_ID != $request->header("user")) {
            throw new \Exception("Operação inválida, você precisa estar logado em sua conta");
        }

        $user = Usuario::where('USUARIO_ID', $id)->first();

        if ($user == null) {
            throw new \Exception("Usuário não encontrado!");
        }

        return ProfileShowResource::make($user);
    }
}
