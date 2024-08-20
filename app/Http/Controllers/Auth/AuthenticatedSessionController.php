<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // $user = User::where('USUARIO_EMAIL', $this->only('email'))->first();

        // if (!$user) {
        //     throw ValidationException::withMessages([
        //         'email' => __('auth.failed')
        //     ]);
        // }
        // if (!Hash::check($this->password, $user->USUARIO_SENHA)) {
        //     throw ValidationException::withMessages([
        //         'email' => __('auth.failed')
        //     ]);
        // }
        // Auth::login($user);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
