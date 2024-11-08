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

        // // dd($request);
        // $request->validate([
        //     'endereco' => 'required|integer',
        //     'produtos' => 'required|array',
        //     'produtos.*.id' => 'required|integer|exists:PRODUTO,PRODUTO_ID',
        //     'produtos.*.quantidade' => 'required|integer|min:1',
        //     'produtos.*.preco' => 'required|numeric|min:0.01',
        // ]);

        // $req = [
        //     'userId' => $request->header('user'),
        //     'pedido' => $request->all(),
        // ];

        return new PedidoStoreResource($request);
    }

    public function update(Request $request) {}
}
