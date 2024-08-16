<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return[
            'role_id' => $this->role_id,
            'role' => $this->role,
            'description' => $this->description,
            'status' => $this->deleted_at?false:true
        ];
    }
}
