<?php

namespace App\Http\Resources;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);

        $passConfirmation = $request->passwordConfirmation;

        $request->merge(['password_confirmation' => $passConfirmation]);

        $request->merge(['cpf' => $cpf]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Usuario::class . ',USUARIO_EMAIL'],
            'cpf' => ['required', 'string', 'min:11', 'max:11', 'unique:' . Usuario::class . ',USUARIO_CPF'],
            'password' => ['required', 'confirmed'],
        ]);
        //confirmation field = password_confirmation

        $user = Usuario::create([
            'USUARIO_NOME' => $request->name,
            'USUARIO_EMAIL' => $request->email,
            'USUARIO_CPF' => $request->cpf,
            'USUARIO_SENHA' => Hash::make($request->password),
        ]);

        if (!$user) {
            throw ValidationException::withMessages([
                'error' => __('auth.failed')
            ]);
        }

        Auth::login($user);

        return ['user' => $user->USUARIO_ID];
    }
}
