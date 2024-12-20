<?php

namespace App\Http\Controllers;

use App\Http\Resources\PedidoIndexResource;
use App\Http\Resources\PedidoStoreResource;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        return new PedidoIndexResource($request->header('user'));
    }

    public function show(Request $request, $id) {}

    public function store(Request $request)
    {
        return new PedidoStoreResource($request);
    }

    public function update(Request $request) {}
}
