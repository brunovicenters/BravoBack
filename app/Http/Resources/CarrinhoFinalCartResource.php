<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Endereco;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarrinhoFinalCartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$request->produtos) {
            throw new \Exception("Informe quais produtos deseja comprar");
        }

        $produtosId = $request->produtos;

        if (!is_array($produtosId)) {
            throw new \Exception("Produtos inválidos");
        }

        if (count($produtosId) == 0) {
            throw new \Exception("Carrinho vazio!");
        }

        if ($request->id == null || !is_numeric($request->id)) {
            throw new \Exception("Usuário inválido");
        }

        $produtos = Produto::ProdutoValido()
            ->with("Imagem")
            ->join('PRODUTO_ESTOQUE', 'PRODUTO.PRODUTO_ID', '=', 'PRODUTO_ESTOQUE.PRODUTO_ID')
            ->join('CARRINHO_ITEM', 'PRODUTO.PRODUTO_ID', '=', 'CARRINHO_ITEM.PRODUTO_ID')
            ->where('PRODUTO_QTD', '>=', 'CARRINHO_ITEM.ITEM_QTD')
            ->where('CARRINHO_ITEM.USUARIO_ID', $request->id)
            ->whereIn('CARRINHO_ITEM.PRODUTO_ID', $produtosId)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->PRODUTO_ID,
                    'name' => $item->PRODUTO_NOME,
                    'price' => ($item->PRODUTO_PRECO - $item->PRODUTO_DESCONTO) * $item->ITEM_QTD,
                    'image' => $item->IMAGEM->first()->IMAGEM_URL ?? null,
                    'quantity' => $item->ITEM_QTD
                ];
            });

        if ($produtos->count() == 0) {
            throw new \Exception("Carrinho vazio!");
        }

        $enderecos = Endereco::where('USUARIO_ID', $request->id)
            ->get()
            ->map(function ($item) {
                return [
                    "enderecoId" => $item->ENDERECO_ID,
                    "street" => $item->ENDERECO_RUA,
                    "nome" => $item->ENDERECO_NOME
                ];
            });

        if ($enderecos->count() == 0) {
        }

        return [
            'produtos' => $produtos,
            'enderecos' => $enderecos
        ];
    }
}
