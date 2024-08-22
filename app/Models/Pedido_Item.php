<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido_Item extends Model
{
    use HasFactory;
    use HasCompositePrimaryKey;

    protected $table = 'PEDIDO_ITEM';
    protected $primaryKey = ['PEDIDO_ID', 'PRODUTO_ID'];
    protected $keyType = 'string';

    public $timestamps = false;
    public $incrementing = false;
    protected $guarded = [];

    public function Produto()
    {
        return $this->hasOne(Produto::class, "PRODUTO_ID", "PRODUTO_ID");
    }
}
