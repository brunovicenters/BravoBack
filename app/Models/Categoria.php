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
            ->selectRaw("PRODUTO.CATEGORIA_ID, SUM(PRODUTO.PRODUTO_VENDAS) as sales_qty")
            ->groupBy("PRODUTO.CATEGORIA_ID")
            ->orderBy("sales_qty", "desc")
            ->limit($limit)
            ->get();

        foreach ($maisVendidas as $value) {
            $produtosCategorias[$value->CATEGORIA_ID] = Produto::ProdutoValido()
                ->where('PRODUTO.CATEGORIA_ID', $value->CATEGORIA_ID)
                ->orderBy('PRODUTO_VENDAS', 'desc')
                ->take(5)
                ->get()
                ->map(function ($produto) {
                    return [
                        'PRODUTO_NOME' => $produto->PRODUTO_NOME,
                        'PRODUTO_PRECO' => $produto->PRODUTO_PRECO,
                        'PRODUTO_DESCONTO' => $produto->PRODUTO_DESCONTO,
                        'IMAGEM_URL' => $produto->Imagem[0]->IMAGEM_URL
                    ];
                });;
        }

        return $produtosCategorias;
    }
}
