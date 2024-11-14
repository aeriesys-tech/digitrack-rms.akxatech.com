<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTemplateDataSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_template_datasource_id' => $this->asset_template_datasource_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->Service),
            'asset_template_id' => $this->asset_template_id,
            'asset_template' => new AssetTemplateResource($this->AssetTemplate),
            'template_zone_id' => $this->template_zone_id,
            'asset_zone' => new TemplateZoneResource($this->TemplateZone),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'data_source_id' => $this->data_source_id,
            'data_source' => new DataSourceResource($this->DataSource),
            'data_source_type_id' => $this->data_source_type_id,
            'script' => $this->script ?? null,
            'asset_datasource_attributes' => TemplateDataSourceValueResource::collection($this->TemplateDataSourceValue)
        ];
    }
}
