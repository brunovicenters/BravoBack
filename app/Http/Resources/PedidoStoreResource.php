<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Pedido;
use App\Models\Pedido_Item;
use App\Models\Produto;
use App\Models\Produto_Estoque;
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

        $produtosCarrinhoIds = [];

        foreach ($request->produtos as $key => $value) {
            $produtosCarrinhoIds[] = $value['id'];
        }

        $qtyProdutos = [];

        foreach ($request->produtos as $key => $value) {
            $qtyProdutos[$produtosCarrinhoIds[$key]] = $value['quantidade'];
        }

        $produtos = Produto::ProdutoValido()
            ->join('PRODUTO_ESTOQUE', 'PRODUTO.PRODUTO_ID', '=', 'PRODUTO_ESTOQUE.PRODUTO_ID')
            ->whereIn('PRODUTO_ID', $produtosCarrinhoIds)
            ->get()
            ->map(function ($prod) use ($qtyProdutos) {

                if ($prod->PRODUTO_QTD < $qtyProdutos[$prod->PRODUTO_ID]) {
                    return false;
                }

                return true;
            });

        if (in_array(false, $produtos->toArray())) {
            return [
                'pedido' => -1,
                'message' => 'out of stock',
            ];
        }

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

        foreach ($produtosCarrinhoIds as $key => $value) {
            Produto_Estoque::where('PRODUTO_ID', $value)
                ->decrement('PRODUTO_QTD', $qtyProdutos[$value]);
        }

        Carrinho::where('USUARIO_ID', $request->header('user'))
            ->whereIn('PRODUTO_ID', $produtosCarrinhoIds)
            ->delete();

        return [
            'pedido' => $pedido->PEDIDO_ID,
            'message' => 'success',
        ];
    }
}
