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
            ->with('Itens')
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
                    'total' => $total
                ];
            });

        if ($pedidos->count() == 0) {
            return [];
        }

        return $pedidos->toArray();
    }
}
