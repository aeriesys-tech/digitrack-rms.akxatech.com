<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_service_id' => $this->asset_service_id,
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->Service),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'status' => $this->deleted_at?false:true
        ];
    }
}
