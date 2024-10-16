<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use Illuminate\Http\Request;
use App\Models\Produto;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isEmpty;

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
            ->ProdutoValido()
            ->where("PRODUTO.PRODUTO_ID", "=", $this["produto"]->PRODUTO_ID)
            ->get()
            ->map(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'categoria' => $produto->CATEGORIA->CATEGORIA_NOME,
                    'qtd' => $produto->ProdutoEstoque->PRODUTO_QTD,
                    'qtdDisponivel' => $produto->ProdutoEstoque->PRODUTO_QTD,
                    'desc' => $produto->PRODUTO_DESC,
                    'imagem' => count($produto->Imagem) > 0 ? $produto->Imagem->map(function ($imagem) {
                        return [
                            'url' => $imagem->IMAGEM_URL,
                        ];
                    }) : null,
                ];
            })
            ->first();

        if (isset($this["user_id"]) && $produto != null) {
            $user = $this["user_id"];
            if ($user) {
                $existingItem = Carrinho::where('USUARIO_ID', $user)
                    ->where('PRODUTO_ID', $produto["id"])
                    ->first();

                if ($existingItem) {
                    $produto["qtdDisponivel"] = $produto["qtd"] - $existingItem->ITEM_QTD;
                }
            }
        }

        $semelhantes = Produto::with('Imagem')
            ->ProdutoValido()
            ->where('CATEGORIA.CATEGORIA_ID', $this["produto"]->CATEGORIA_ID)
            ->where('PRODUTO.PRODUTO_ID', '!=', $this["produto"]->PRODUTO_ID)
            ->take(5)
            ->get()
            ->map(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'imagem' => !isEmpty($produto->Imagem) || $produto->Imagem ? $produto->Imagem[0]->IMAGEM_URL : null,
                ];
            });

        return [
            'produto' => $produto,
            'semelhantes' => $semelhantes,
        ];
    }
}
