<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CheckResource;
use App\Http\Resources\AssetResource;
use App\Http\Resources\PlantResource;

class AssetCheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_check_id' => $this->asset_check_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'check_id' => $this->check_id,
            'check' => new CheckResource($this->Check),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($this->AssetZone),
            'status' => $this->deleted_at?false:true,
            'lcl' => $this->lcl,
            'ucl' => $this->ucl,
            'default_value' => $this->default_value,
            'asset_service_id' => $this->asset_service_id,
            'asset_service' => new AssetCheckServiceResource($this->AssetService)
        ];
    }
}
