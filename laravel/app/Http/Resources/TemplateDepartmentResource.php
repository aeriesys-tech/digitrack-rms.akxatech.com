<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateDepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template_department_id' => $this->template_department_id,
            'asset_id' => $this->asset_id,
            'department_id' => $this->department_id,
            'department' => new DepartmentResource($this->Department),
        ];
    }
}
