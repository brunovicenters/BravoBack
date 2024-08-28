<?php

namespace App\Http\Resources;

use App\Models\Categoria;
use App\Models\Produto;
use COM;
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
            ->where('PRODUTO_PRECO', '>', 0)
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

        $produtos = $produtosQuery->paginate(20);

        $produtoMaisVendido = Produto::withCount("PedidoItem")
            ->join('CATEGORIA', 'PRODUTO.CATEGORIA_ID', '=', 'CATEGORIA.CATEGORIA_ID')
            ->join('PRODUTO_ESTOQUE', 'PRODUTO_ESTOQUE.PRODUTO_ID', '=', 'PRODUTO.PRODUTO_ID')
            ->where('PRODUTO_ESTOQUE.PRODUTO_QTD', '>', 0)
            ->where('CATEGORIA_ATIVO', '=', 1)
            ->where('PRODUTO_ATIVO', '=', 1)
            ->whereColumn('PRODUTO_PRECO', '>', "PRODUTO_DESCONTO")
            ->orderBy('pedido_item_count', 'desc')
            ->first(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'imagem' => $produto->Imagem[0]->IMAGEM_URL
                ];
            });


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
