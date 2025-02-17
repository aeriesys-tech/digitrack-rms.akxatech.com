<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Service;
use App\Models\AssetZone;

class AssetCheckServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $service = Service::where('service_id', $this->service_id)->first();
        $zone = AssetZone::where('asset_zone_id', $this->asset_zone_id,)->first();
        return [
            'asset_service_id' => $this->asset_service_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->Service),
            'service_name' => $service->service_name ?? null,
            'asset_id' => $this->asset_id,
            // 'asset_template' => new AssetTemplateResource($this->AssetTemplate),
            'asset_zone_id' => $this->asset_zone_id,
            // 'asset_zone' => new TemplateZoneResource($this->TemplateZone),
            'zone_name' => $zone->zone_name,
            'plant_id' => $this->plant_id,
            // 'plant' => new PlantResource($this->Plant),
            'service_type_id' => $this->service_type_id,
            'service_type' => new ServiceTypeResource($this->ServiceType),
            'status' => $this->deleted_at?false:true,
            // 'asset_service_attributes' => TemplateServiceValueResource::collection($this->TemplateServiceValue)
        ];
    }
}
