<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetDataSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_data_source_id' => $this->asset_data_source_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->Service),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($this->AssetZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'data_source_type_id' => $this->data_source_type_id,
            'data_source_type' => new DataSourceTypeResource($this->DataSourceType)
        ];
    }
}
