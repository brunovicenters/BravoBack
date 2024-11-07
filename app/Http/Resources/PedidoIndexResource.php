<?php

namespace App\Http\Resources;

use App\Models\Pedido;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $id = $this->resource;

        $pedidos = Pedido::where('USUARIO_ID', $id)
            ->with('Status')
            ->with('Endereco')
            ->with('Itens.Produto')
            ->get()
            ->map(function ($item) {

                $item->PEDIDO_DATA = $item->PEDIDO_DATA ? (new DateTime($item->PEDIDO_DATA))->format('d/m/Y') : null;

                $subtotal = $item->Itens->map(function ($item) {
                    return $item->ITEM_PRECO * $item->ITEM_QTD;
                });

                $total = $subtotal->sum();

                return [
                    'id' => $item->PEDIDO_ID,
                    'status' => $item->STATUS->STATUS_DESC,
                    'data' => $item->PEDIDO_DATA,
                    'endereco' => $item->Endereco->first()->ENDERECO_NOME . " - " . $item->Endereco->first()->ENDERECO_LOGRADOURO,
                    'total' => $total,
                    'itens' => $item->Itens->map(function ($item) {
                        return [
                            'id' => $item->PRODUTO_ID,
                            'imagem' => $item->PRODUTO->PRODUTO_IMAGEM ?? null,
                            'nome' => $item->PRODUTO->PRODUTO_NOME,
                            'preco' => $item->ITEM_PRECO,
                            'qtd' => $item->ITEM_QTD
                        ];
                    })->toArray()
                ];
            });

        if ($pedidos->count() == 0) {
            return [];
        }

        return $pedidos->toArray();
    }
}
