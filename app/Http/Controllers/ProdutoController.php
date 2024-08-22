<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProdutoIndexResource;
use App\Http\Resources\ProdutoShowResource;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $promo = true;
        $price = 25;
        $categoria = 3;

        $request = [$promo, $price, $categoria];

        return new ProdutoIndexResource($request);
    }

    public function show(Produto $produto)
    {
        return new ProdutoShowResource($produto);
    }
}
