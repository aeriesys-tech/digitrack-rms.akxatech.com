<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlantResource;
use App\Http\Resources\AssetTypeResource;
use App\Models\AssetAttribute;
use App\Models\TemplateDepartment;

class AssetTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_attributes = AssetAttribute::whereHas('AssetAttributeTypes', function($que){
            $que->where('asset_type_id', $this->asset_type_id);
        })->get();
        
        $asset_department_id = TemplateDepartment::where('asset_template_id', $this->asset_template_id)->pluck('department_id');
        return [
            'asset_template_id' => $this->asset_template_id,
            'area_id' => $this->area_id,
            'area' => new AreaResource($this->Area),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'template_code' => $this->template_code,
            'template_name' => $this->template_name,
            'no_of_zones' => $this->no_of_zones,
            'asset_type_id' => $this->asset_type_id,
            'asset_type' => new AssetTypeResource($this->AssetType),
            // 'asset_parameter_values' => AssetParameterResource::collection($this->AssetParameters),
            'status' => $this->deleted_at?false:true,
            'asset_attributes' => TemplateAttributeVResource::collection($asset_attributes->map(function ($templateAttribute) {
                return ['resource' => $templateAttribute, 'asset_template_id' => $this->asset_template_id];
            })),
            // 'asset_parameters' => $asset_parameters,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'functional_id' => $this->functional_id,
            'functional' => new FunctionalResource($this->Functional),
            'section_id' => $this->section_id,
            'section' => new SectionResource($this->Section),
            'radius' => $this->radius,
            'zone_name' => TemplateZoneResource::collection($this->Zones),
            'asset_department_ids' => TemplateDepartmentResource::collection($this->TemplateDepartment),
            'asset_departments' => $asset_department_id,
            'geometry_type' => $this->geometry_type,
            'height' => $this->height,
            'diameter' => $this->diameter,
            'scanner_code' => $this->scanner_code,
            'ppms_code' => $this->ppms_code
        ];
    }
}
