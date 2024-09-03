<?php

namespace App\Http\Resources;

use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $promocao = Produto::ProdutoValido()
            ->with('Imagem')
            ->where('PRODUTO_DESCONTO', '>', 0)
            ->orderBy('PRODUTO_DESCONTO', 'desc')
            ->take(5)
            ->get()
            ->map(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'imagem' => $produto->Imagem->first()->IMAGEM_URL ?? null,
                ];
            });

        $produtosMaisVendidos = Produto::MaisVendido(5)
            ->map(function ($produto) {
                return [
                    'id' => $produto->PRODUTO_ID,
                    'nome' => $produto->PRODUTO_NOME,
                    'preco' => $produto->PRODUTO_PRECO,
                    'desconto' => $produto->PRODUTO_DESCONTO,
                    'imagem' => $produto->Imagem->first()->IMAGEM_URL ?? null,
                ];
            });;

        $categoriasMaisVendidas = Categoria::MaisVendidas(3);

        return [
            'promocao' => $promocao,
            'produtosMaisVendidos' => $produtosMaisVendidos,
            'categoriasMaisVendidas' => $categoriasMaisVendidas
        ];
    }
}
