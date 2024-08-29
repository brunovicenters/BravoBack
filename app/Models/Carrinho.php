<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    use HasFactory;

    use HasFactory;
    use HasCompositePrimaryKey;


    protected $table = 'CARRINHO_ITEM';
    protected $primaryKey = ['USUARIO_ID', 'PRODUTO_ID'];
    protected $keyType = 'string';

    protected $fillable = ['USUARIO_ID', 'PRODUTO_ID', 'ITEM_QTD'];
    public $timestamps = false;
    protected $guarded = [];
    public $incrementing = false;

    public function Produto()
    {
        return $this->hasOne(Produto::class, 'PRODUTO_ID', 'PRODUTO_ID');
    }
}
