<?php

namespace App\Http\Resources;

use App\Models\Pedido;
use App\Models\Pedido_Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $request->validate([
            'endereco' => 'required|integer',
            'produtos' => 'required|array',
            'produtos.*.id' => 'required|integer|exists:PRODUTO,PRODUTO_ID',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco' => 'required|numeric|min:0.01',
        ]);

        $pedido = Pedido::create([
            'ENDERECO_ID' => $request->endereco,
            'USUARIO_ID' => $request->header('user'),
            'STATUS_ID' => 5,
            'PEDIDO_DATA' => date('Y-m-d'),
        ]);

        foreach ($request->produtos as $key => $value) {
            Pedido_Item::create([
                'PEDIDO_ID' => $pedido->PEDIDO_ID,
                'PRODUTO_ID' => $value['id'],
                'ITEM_QTD' => $value['quantidade'],
                'ITEM_PRECO' => $value['preco'],
            ]);
        }

        return [
            'pedido' => $pedido->PEDIDO_ID,
            'status' => 200,
        ];
    }
}
