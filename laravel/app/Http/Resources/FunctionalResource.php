<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FunctionalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'functional_id' => $this->functional_id,
            'functional_code' => $this->functional_code,
            'functional_name' => $this->functional_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
