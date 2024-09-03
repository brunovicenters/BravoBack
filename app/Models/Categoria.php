<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;


    protected $table = "CATEGORIA";
    protected $primaryKey = "CATEGORIA_ID";
    public $timestamps = false;

    public $guarded = [];

    public function Produtos()
    {
        return $this->hasMany(Produto::class, 'CATEGORIA_ID', 'CATEGORIA_ID');
    }

    public static function MaisVendidas($limit)
    {
        $maisVendidas = Produto::ProdutoValido()
            ->selectRaw("PRODUTO.CATEGORIA_ID, CATEGORIA.CATEGORIA_NOME, SUM(PRODUTO.PRODUTO_VENDAS) as sales_qty")
            ->groupBy("PRODUTO.CATEGORIA_ID")
            ->orderBy("sales_qty", "desc")
            ->limit($limit)
            ->get();

        $produtosCategorias = [];

        foreach ($maisVendidas as $value) {

            $categoria["id"] = $value->CATEGORIA_ID;
            $categoria["nome"] = ucfirst($value->CATEGORIA_NOME);

            $categoria["produtos"] = Produto::ProdutoValido()
                ->where('PRODUTO.CATEGORIA_ID', $value->CATEGORIA_ID)
                ->orderBy('PRODUTO_VENDAS', 'desc')
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
                });;

            $produtosCategorias[] = $categoria;
        }

        return $produtosCategorias;
    }
}
