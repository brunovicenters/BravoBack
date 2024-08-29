<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
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
        $produto = Produto::with("Imagem")
            ->join("PRODUTO_ESTOQUE", "PRODUTO.PRODUTO_ID", "=", "PRODUTO_ESTOQUE.PRODUTO_ID")
            ->join("CATEGORIA", "PRODUTO.CATEGORIA_ID", "=", "CATEGORIA.CATEGORIA_ID")
            ->where("PRODUTO.PRODUTO_ID", "=", $this["produto"]->PRODUTO_ID)
            ->where("CATEGORIA_ATIVO", "=", 1)
            ->where("PRODUTO_ATIVO", '=', 1)
            ->where('PRODUTO_QTD', '>', 0)
            ->where('PRODUTO_PRECO', '>', 0)
            ->whereColumn("PRODUTO_PRECO", ">", "PRODUTO_DESCONTO")
            ->first();

        if (isset($this["user_id"])) {
            $user = $this["user_id"];
            if ($user) {
                $existingItem = Carrinho::where('USUARIO_ID', $user)
                    ->where('PRODUTO_ID', $produto->PRODUTO_ID)
                    ->first();

                if ($existingItem) {
                    $qtdAvailable = $produto->ProdutoEstoque->PRODUTO_QTD - $existingItem->ITEM_QTD;
                } else {
                    $qtdAvailable = $produto->ProdutoEstoque->PRODUTO_QTD;
                }
                $produto["qtdAvailable"] = $qtdAvailable;
            }
        }

        $semelhantes = Produto::with('Imagem')
            ->join("PRODUTO_ESTOQUE", "PRODUTO.PRODUTO_ID", "=", "PRODUTO_ESTOQUE.PRODUTO_ID")
            ->join("CATEGORIA", "PRODUTO.CATEGORIA_ID", "=", "CATEGORIA.CATEGORIA_ID")
            ->where('CATEGORIA.CATEGORIA_ID', $this["produto"]->CATEGORIA_ID)
            ->where('PRODUTO.PRODUTO_ID', '!=', $this["produto"]->PRODUTO_ID)
            ->where("CATEGORIA_ATIVO", "=", 1)
            ->where("PRODUTO_ATIVO", '=', 1)
            ->where('PRODUTO_QTD', '>', 0)
            ->where('PRODUTO_PRECO', '>', 0)
            ->whereColumn("PRODUTO_PRECO", ">", "PRODUTO_DESCONTO")
            ->take(5)
            ->get()
            ->map(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'imagem' => $produto->Imagem[0]->IMAGEM_URL,
                ];
            });

        return [
            'produto' => $produto,
            'semelhantes' => $semelhantes,
        ];
    }
}
