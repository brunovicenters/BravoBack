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
        $isPromo = $this[0] ? true : false;
        // $priceLimit = $this[1];
        // $categoryId = $this[2];

        $produtosQuery = Produto::query();

        if ($isPromo) {
            $produtosQuery->where("PRODUTO_DESCONTO", '>', 0);
        }

        $produtos = $produtosQuery->get();

        return ["produtos" => $produtos];
    }
}
