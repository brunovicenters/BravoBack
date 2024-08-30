<?php

namespace App\Http\Resources;

use App\Models\Categoria;
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
            ->ProdutoValido();

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

        $produtos = $produtosQuery->paginate(20);

        $produtoMaisVendido = Produto::MaisVendido(1);


        $categorias = Categoria::where('CATEGORIA_ATIVO', '=', 1)
            ->get()
            ->map(function ($categoria) {
                return [
                    'id' => $categoria->CATEGORIA_ID,
                    'nome' => $categoria->CATEGORIA_NOME
                ];
            });

        return [
            "produtos" => $produtos,
            "maisVendido" => $produtoMaisVendido,
            "categorias" => $categorias
        ];
    }
}
