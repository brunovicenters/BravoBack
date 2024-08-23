<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public $preserveKeys = true;

    public function toArray(Request $request): array
    {
        $this->Imagem;
        $this->Categoria;
        $this->ProdutoEstoque;

        // TODO: Tratar quantidade em carrinho

        $produto = $this->resource;

        $semelhantes = Produto::with('Imagem')
            ->where('CATEGORIA_ID', $this->CATEGORIA_ID)
            ->where('PRODUTO_ID', '!=', $this->PRODUTO_ID)
            ->take(5)
            ->get()->map(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'imagem' => $produto->Imagem[0]->IMAGEM_URL,
                ];
            });;

        return [
            'produto' => $produto,
            'semelhantes' => $semelhantes
        ];
    }
}
