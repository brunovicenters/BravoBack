<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdutoIndexResource;
use App\Http\Resources\ProdutoShowResource;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $search = [];

        // Use $request to get the query string parameters
        if ($request->has('promocao')) {
            $promocao = true;
            $search['promocao'] = $promocao;
        }

        if ($request->has('preco')) {
            $preco = $request->input('preco');
            $search['preco'] = $preco;
        }

        if ($request->has('categorias')) {
            $categorias = $request->input('categorias');

            $search['categorias'] = json_decode($categorias, true);
        }

        if ($request->has('busca')) {
            $busca = $request->input('busca');
            $search['busca'] = $busca;
        }

        return new ProdutoIndexResource($search);
    }

    public function show(Produto $produto, Request $request)
    {
        $req["produto"] = $produto;

        if ($request->has("userId")) {
            $req["user_id"] = $request->input("userId");
        }

        return new ProdutoShowResource($req);
    }
}
