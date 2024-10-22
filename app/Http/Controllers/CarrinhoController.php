<?php

namespace App\Http\Controllers;

use App\Http\Resources\CarrinhoDeleteResource;
use App\Http\Resources\CarrinhoIndexResource;
use App\Http\Resources\CarrinhoStoreResource;
use App\Http\Resources\CarrinhoUpdateResource;
use App\Models\Carrinho;
use App\Models\Produto;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{

    // Return all cart's items from database
    public function index(Request $request)
    {
        return new CarrinhoIndexResource($request->header('user'));
    }

    // Add item to cart OR update cart quantity
    public function store(Request $request)
    {
        if (Produto::find($request->produtoId) == null) {
            throw new \Exception("Produto não encontrado!");
        }

        if ($request->quantity <= 0 || !is_numeric($request->quantity)) {
            throw new \Exception("Quantidade inválida!");
        }

        $item = [
            'USUARIO_ID' => $request->header('user'),
            'PRODUTO_ID' => $request->produtoId,
            'ITEM_QTD' => $request->quantity
        ];

        return new CarrinhoStoreResource($item);
    }

    // Update item quantity
    public function update(Request $request)
    {

        if (Produto::find($request->produtoId) == null) {
            throw new \Exception("Produto não encontrado!");
        }

        if ($request->quantity < 0 || !is_numeric($request->quantity)) {
            throw new \Exception("Quantidade inválida!");
        }

        $item = [
            'USUARIO_ID' => $request->header('user'),
            'PRODUTO_ID' => $request->produtoId,
            'ITEM_QTD' => $request->quantity
        ];

        return new CarrinhoUpdateResource($item);
    }

    // Remove item from cart
    public function destroy(Request $request, $produto)
    {
        $item = [
            'USUARIO_ID' => $request->header('user'),
            'PRODUTO_ID' => $produto
        ];

        dd($item);

        return new CarrinhoDeleteResource($item);
    }
}
