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
            ->join('PRODUTO_ESTOQUE', 'PRODUTO.PRODUTO_ID', '=', 'PRODUTO_ESTOQUE.PRODUTO_ID')
            ->join('CARRINHO_ITEM', 'PRODUTO.PRODUTO_ID', '=', 'CARRINHO_ITEM.PRODUTO_ID')
            ->where('CARRINHO_ITEM.USUARIO_ID', $id)
            ->get()
            ->map(function ($item) {

                if ($item->PRODUTO_QTD <= 0 || $item->ITEM_QTD <= 0 || $item->PRODUTO_QTD == null) {
                    Carrinho::where('PRODUTO_ID', $item->PRODUTO_ID)->delete();
                    return;
                }

                $changed = false;
                if ($item->ITEM_QTD > $item->PRODUTO_QTD) {
                    $item->ITEM_QTD = $item->PRODUTO_QTD;
                    $item->save();
                    $changed = true;
                }

                return [
                    'id' => $item->PRODUTO_ID,
                    'name' => $item->PRODUTO_NOME,
                    'quantity' => $item->ITEM_QTD,
                    'stock' => $item->PRODUTO_QTD,
                    'price' => ($item->PRODUTO_PRECO - $item->PRODUTO_DESCONTO) * $item->ITEM_QTD,
                    'image' => $item->IMAGEM->first()->IMAGEM_URL ?? null,
                    'changedQty' => $changed
                ];
            })->filter();

        return [
            'carrinho' => $produtos
        ];
    }
}
