<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'module_id' => $this->module_id,
            'module_name' => $this->module_name,
            'description' => $this->description
        ];
    }
}
