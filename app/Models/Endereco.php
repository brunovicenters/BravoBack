<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $table = "ENDERECO";
    protected $primaryKey = "ENDERECO_ID";
    public $timestamps = false;

    public $guarded = [];

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class, 'USUARIO_ID');
    }

    public function Pedido()
    {
        return $this->hasMany(Pedido::class, 'ENDERECO_ID');
    }
}
