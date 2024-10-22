<?php

namespace App\Http\Resources;

use App\Models\Carrinho;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarrinhoDeleteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if (Produto::find($this["PRODUTO_ID"]) == null) {
            throw new \Exception("Produto não existente!");
        }

        $produto = Carrinho::where('USUARIO_ID', $this["USUARIO_ID"])
            ->where('PRODUTO_ID', $this["PRODUTO_ID"])
            ->first();

        if ($produto == null) {

            return [
                'msg' => 'not found',
            ];
        }

        try {
            $produto->delete();
        } catch (\Throwable $th) {
            return [
                'msg' => 'error',
            ];
        }

        return [
            'msg' => 'deleted',
        ];
    }
}
