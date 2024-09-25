<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public $table = 'PEDIDO_STATUS';
    public $primaryKey = 'STATUS_ID';
    public $timestamps = false;

    protected $guarded = [];

    public function Pedidos()
    {
        return $this->hasMany(Pedido::class, "STATUS_ID", "STATUS_ID");
    }
}
