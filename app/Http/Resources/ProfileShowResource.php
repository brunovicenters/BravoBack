<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Pedido;
use App\Models\Pedido_Item;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user["id"] = $this->USUARIO_ID;
        $user["name"] = $this->USUARIO_NOME;
        $user["email"] = $this->USUARIO_EMAIL;
        $user["cpf"] = substr($this->USUARIO_CPF, 0, 3) . '.***.***-' . substr($this->USUARIO_CPF, -2);

        $lastBuyIds = Pedido::where('USUARIO_ID', $user["id"])->get();

        if ($lastBuyIds->count() > 0) {
            $arrayPedidosIds = $lastBuyIds->map(function ($item) {
                return $item->PEDIDO_ID;
            })->toArray();

            $itens = Pedido_Item::whereIn('PEDIDO_ID', $arrayPedidosIds)->get();

            $arrayItensId = $itens->map(function ($item) {
                return $item->PRODUTO_ID;
            })->toArray();

            $compreNovamente = Produto::ProdutoValido()
                ->whereIn('PRODUTO.PRODUTO_ID', $arrayItensId)
                ->get()
                ->map(function ($produto) {
                    return [
                        'id' => $produto->PRODUTO_ID,
                        'nome' => $produto->PRODUTO_NOME,
                        'preco' => $produto->PRODUTO_PRECO,
                        'desconto' => $produto->PRODUTO_DESCONTO,
                        'imagem' => $produto->Imagem->first()->IMAGEM_URL ?? null,
                    ];
                });
        }

        return [
            'user' => $user,
            'compreNovamente' => $compreNovamente ?? [],
        ];
    }
}
