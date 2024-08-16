<?php

namespace App\Http\Resources;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Resources\ModuleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $module =  Module::where('module_id', $this->module_id)->first();
        return [
            'ability_id' => $this->ability_id,
            'ability' => $this->ability,
            'description' => $this->description,
            'module_id' => $this->module_id,
            'module'=> new ModuleResource($module),
            'status' => $this->status
        ];
    }
}
