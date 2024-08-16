<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlantResource;
use App\Http\Resources\EquipmentTypeResource;

class EquipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'equipment_id' => $this->equipment_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'equipment_type_id' => $this->equipment_type_id,
            'equipment_type' => new EquipmentTypeResource($this->EquipmentType),
            'equipment_code' => $this->equipment_code,
            'equipment_name' => $this->equipment_name,
            'description' => $this->description,
            'status' => $this->deleted_at?false:true
        ];
    }
}
