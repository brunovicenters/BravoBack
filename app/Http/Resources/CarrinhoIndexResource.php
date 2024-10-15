<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarrinhoIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $id = $this->resource;

        $produtos = Produto::with("Imagem")
            ->ProdutoValido()
            ->join('CARRINHO_ITEM', 'PRODUTO.PRODUTO_ID', '=', 'CARRINHO_ITEM.PRODUTO_ID')
            ->where('CARRINHO_ITEM.USUARIO_ID', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->PRODUTO_ID,
                    'name' => $item->PRODUTO_NOME,
                    'quantity' => $item->ITEM_QTD,
                    'price' => $item->PRODUTO_PRECO,
                    'image' => $item->IMAGEM->first()->IMAGEM_URL ?? null
                ];
            });

        return [
            'carrinho' => $produtos
        ];
    }
}
