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
            $compreNovamente = Pedido::orderBy('PEDIDO_ID', 'desc')
                ->with(['Produto' => function ($query) {
                    $query->with('Imagem')
                        ->select('PRODUTO.PRODUTO_ID as id', 'PRODUTO.PRODUTO_NOME as nome', 'PRODUTO.PRODUTO_PRECO as preco', 'PRODUTO.PRODUTO_DESCONTO as desconto')
                        ->limit(10);
                }])
                ->get()
                ->flatMap(function ($pedido) {
                    return $pedido->Produto->map(function ($produto) {
                        return [
                            'id' => $produto->id,
                            'nome' => $produto->nome,
                            'preco' => $produto->preco,
                            'desconto' => $produto->desconto,
                            'imagem' => $produto->Imagem->first()->IMAGEM_URL ?? null,
                        ];
                    });
                })
                ->unique('id')
                ->values();

            dd($compreNovamente);
        }

        return [
            'user' => $user,
            'compreNovamente' => $compreNovamente ?? [],
        ];
    }
}
