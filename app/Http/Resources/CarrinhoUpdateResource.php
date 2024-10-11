<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Produto;
use App\Models\Produto_Estoque;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarrinhoUpdateResource extends JsonResource
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
                ->get()->count() > 0) || $this["ITEM_QTD"] < 0
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

        if (
            !(Carrinho::where('USUARIO_ID', $this["USUARIO_ID"])
                ->where('PRODUTO_ID', $this["PRODUTO_ID"])
                ->get()->count() > 0
            )
        ) {
            return [
                'msg' => 'invalid item',
            ];
        };

        if ($this["ITEM_QTD"] == 0) {
            $item = Carrinho::where('USUARIO_ID', $this["USUARIO_ID"])
                ->where('PRODUTO_ID', $this["PRODUTO_ID"])
                ->first();

            $item->delete();

            return [
                'msg' => 'Item removido com sucesso!',
            ];
        };

        $produto = Produto_Estoque::where('PRODUTO_ID', $this["PRODUTO_ID"])
            ->where('PRODUTO_QTD', '>=', $this["ITEM_QTD"])
            ->first();

        if (!$produto) {
            return [
                'msg' => 'qty insufficient',
            ];
        }

        $item = Carrinho::where('USUARIO_ID', $this["USUARIO_ID"])
            ->where('PRODUTO_ID', $this["PRODUTO_ID"])
            ->first();

        $item->update([
            'ITEM_QTD' => $this["ITEM_QTD"]
        ]);

        return [
            'msg' => 'Quantidade atualizada com sucesso!',
        ];
    }
}
