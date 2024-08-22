<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagem extends Model
{
    use HasFactory;
    protected $table = "PRODUTO_IMAGEM";
    protected $primaryKey = "IMAGEM_ID";
    public $timestamps = false;
    public $guarded = [];
}
