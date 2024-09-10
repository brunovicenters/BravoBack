<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // dd(1);
        return new LoginResource($request);
    }

    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
