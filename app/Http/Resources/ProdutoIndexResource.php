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

        $produtosQuery = Produto::query()
            ->where("PRODUTO_ATIVO", '=', 1);

        if ($isPromo) {
            $produtosQuery->where("PRODUTO_DESCONTO", '>', 0);
        }

        if ($priceLimit) {
            $produtosQuery->where("PRODUTO_PRECO", '<=', $priceLimit);
        }

        if ($categoryId) {
            $produtosQuery->whereIn("CATEGORIA_ID", $categoryId);
        }

        $produtos = $produtosQuery->get();

        return ["produtos" => $produtos];
    }
}
