<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'equipment_type_id' => $this->equipment_type_id,
            'equipment_type_code' => $this->equipment_type_code,
            'equipment_type_name' => $this->equipment_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}
