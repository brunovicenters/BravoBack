<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Usuario::where('USUARIO_EMAIL', $request->email)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed')
            ]);
        }

        if (!Hash::check($request->password, $user->USUARIO_SENHA)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed')
            ]);
        }

        Auth::login($user);

        return [
            'user' => $user->USUARIO_ID,
        ];
    }
}
