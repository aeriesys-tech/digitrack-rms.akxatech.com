<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return[
            'plant_id' => $this->plant_id,
            'plant_code' => $this->plant_code,
            'plant_name'=>$this->plant_name,
            'area_id' => $this->area_id,
            'Area' => new AreaResource($this->Area),
            'status' => $this->deleted_at?false:true,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'radius' => $this->radius
        ];
    }
}
