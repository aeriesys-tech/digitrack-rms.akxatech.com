<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ability_id' => $this->ability_id,
            'ability' => $this->ability,
            'description' => $this->description,
            'module'=> new ModuleResource($this->Module),
        ];
    }
}
