<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public $preserveKeys = true;

    public function toArray(Request $request): array
    {
        $produto = 1;

        return ['produto' => $produto];
    }
}
