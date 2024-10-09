<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Produto;
use App\Models\Produto_Estoque;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarrinhoStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (
            !(Produto_Estoque::where('PRODUTO_ID', $this["PRODUTO_ID"])
                ->where('PRODUTO_QTD', '>=', $this["ITEM_QTD"])
                ->get()->count() > 0)
        ) {
            return [
                'msg' => 'invalid qty',
            ];
        }

        if (
            !(Produto::ProdutoValido()
                ->where('PRODUTO.PRODUTO_ID', $this["PRODUTO_ID"])
                ->get()->count() > 0
            )
        ) {
            return [
                'msg' => 'invalid product',
            ];
        }

        $existingItem = Carrinho::where('USUARIO_ID', $this["USUARIO_ID"])
            ->where('PRODUTO_ID', $this["PRODUTO_ID"])
            ->first();

        if ($existingItem) {
            $produto = Produto_Estoque::where('PRODUTO_ID', $existingItem["PRODUTO_ID"])
                ->where('PRODUTO_QTD', '>=', ($this["ITEM_QTD"] + $existingItem["ITEM_QTD"]))
                ->first();

            if (!$produto) {
                return [
                    'msg' => 'qty insufficient',
                ];
            }

            $existingItem->update([
                'ITEM_QTD' => $existingItem["ITEM_QTD"] + $this["ITEM_QTD"]
            ]);
        } else {
            Carrinho::create($this->resource);
        }

        return [
            'msg' => 'success',
        ];
    }
}
