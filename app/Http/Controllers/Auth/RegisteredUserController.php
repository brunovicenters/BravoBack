<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegisterResource;
use Illuminate\Http\Request;


class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request) //: RedirectResponse
    {
        // dd(2);
        return new RegisterResource($request);
    }
}
