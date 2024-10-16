<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = "PRODUTO";
    protected $primaryKey = "PRODUTO_ID";
    public $timestamps = false;

    public $guarded = [];

    public function Imagem()
    {
        return $this->hasMany(Imagem::class, 'PRODUTO_ID', 'PRODUTO_ID');
    }

    public function Categoria()
    {
        return $this->belongsTo(Categoria::class, 'CATEGORIA_ID', 'CATEGORIA_ID');
    }

    public function ProdutoEstoque()
    {
        return $this->hasOne(Produto_Estoque::class, 'PRODUTO_ID', 'PRODUTO_ID');
    }

    public function PedidoItem()
    {
        return $this->hasMany(Pedido_Item::class, 'PRODUTO_ID', 'PRODUTO_ID');
    }

    public function scopeProdutoValido($query)
    {
        return $query->join("CATEGORIA", "PRODUTO.CATEGORIA_ID", "=", "CATEGORIA.CATEGORIA_ID")
            ->where("CATEGORIA_ATIVO", "=", 1)
            ->where("PRODUTO_ATIVO", '=', 1)
            ->where('PRODUTO_PRECO', '>', 0)
            ->whereColumn("PRODUTO_PRECO", ">", "PRODUTO_DESCONTO");
    }

    public static function MaisVendido($limit)
    {
        return Produto::ProdutoValido()
            ->with("Imagem")
            ->orderBy('PRODUTO_VENDAS', 'desc')
            ->take($limit)
            ->get();
    }
}
