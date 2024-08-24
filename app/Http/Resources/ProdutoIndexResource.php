<?php

namespace App\Http\Resources;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isPromo = isset($this['promocao']) ? true : false;
        $priceLimit = isset($this['preco']) ? $this['preco'] : false;
        $categoryId = isset($this['categorias']) ? $this['categorias'] : false;
        $produtoBuscado = isset($this['busca']) ? $this['busca'] : false;

        $produtosQuery = Produto::query()
            ->join("PRODUTO_ESTOQUE", "PRODUTO.PRODUTO_ID", "=", "PRODUTO_ESTOQUE.PRODUTO_ID")
            ->join("CATEGORIA", "PRODUTO.CATEGORIA_ID", "=", "CATEGORIA.CATEGORIA_ID")
            ->where("CATEGORIA_ATIVO", "=", 1)
            ->where("PRODUTO_ATIVO", '=', 1)
            ->where('PRODUTO_QTD', '>', 0)
            ->whereColumn("PRODUTO_PRECO", ">", "PRODUTO_DESCONTO");

        if ($isPromo) {
            $produtosQuery->where("PRODUTO_DESCONTO", '>', 0);
        }

        if ($priceLimit) {
            $produtosQuery->where("PRODUTO_PRECO", '<=', $priceLimit);
        }

        if ($categoryId) {
            $produtosQuery->whereIn("CATEGORIA_ID", $categoryId);
        }

        if ($produtoBuscado) {
            $produtosQuery->where("PRODUTO_NOME", 'like', '%' . $produtoBuscado . '%');
        }

        $produtos = $produtosQuery->get();

        return ["produtos" => $produtos];
    }
}
