<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetDepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_department_id' => $this->asset_department_id,
            'asset_id' => $this->asset_id,
            'department_id' => $this->department_id,
            'department' => new DepartmentResource($this->Department),
        ];
    }
}
