<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'department_id' => $this->department_id,
            'department_code' => $this->department_code,
            'department_name' => $this->department_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
