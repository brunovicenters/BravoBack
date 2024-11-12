<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    public $table = 'PEDIDO';
    public $primaryKey = 'PEDIDO_ID';
    public $timestamps = false;

    protected $guarded = [];

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class, "USUARIO_ID", "USUARIO_ID");
    }

    public function Endereco()
    {
        return $this->belongsTo(Endereco::class, "ENDERECO_ID", "ENDERECO_ID");
    }

    public function Itens()
    {
        return $this->hasMany(Pedido_Item::class, "PEDIDO_ID", "PEDIDO_ID");
    }

    public function Status()
    {
        return $this->belongsTo(Status::class, "STATUS_ID", "STATUS_ID");
    }

    public function Produto()
    {
        return $this->belongsToMany(Produto::class, 'PEDIDO_ITEM', 'PEDIDO_ID', 'PRODUTO_ID')
            ->withPivot('ITEM_QTD', 'ITEM_PRECO');
    }
}
