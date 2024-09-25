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
        return $this->hasOne(Usuario::class, "USUARIO_ID", "USUARIO_ID");
    }

    public function Itens()
    {
        return $this->hasMany(Pedido_Item::class, "PEDIDO_ID", "PEDIDO_ID");
    }

    public function Status()
    {
        return $this->hasOne(Status::class, "STATUS_ID", "STATUS_ID");
    }
}
