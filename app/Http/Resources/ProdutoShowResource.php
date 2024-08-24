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

        if ($this->CATEGORIA_ATIVO != 1 || $this->PRODUTO_ATIVO != 1 || $this->PRODUTO_QTD <= 0 || $this->PRODUTO_PRECO <= 0 || $this->PRODUTO_PRECO <= $this->PRODUTO_DESCONTO) {
            $produto = null;
        }

        $semelhantes = Produto::with('Imagem')
            ->join("PRODUTO_ESTOQUE", "PRODUTO.PRODUTO_ID", "=", "PRODUTO_ESTOQUE.PRODUTO_ID")
            ->join("CATEGORIA", "PRODUTO.CATEGORIA_ID", "=", "CATEGORIA.CATEGORIA_ID")
            ->where('CATEGORIA.CATEGORIA_ID', $this->CATEGORIA_ID)
            ->where('PRODUTO.PRODUTO_ID', '!=', $this->PRODUTO_ID)
            ->where("CATEGORIA_ATIVO", "=", 1)
            ->where("PRODUTO_ATIVO", '=', 1)
            ->where('PRODUTO_QTD', '>', 0)
            ->where('PRODUTO_PRECO', '>', 0)
            ->whereColumn("PRODUTO_PRECO", ">", "PRODUTO_DESCONTO")
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
