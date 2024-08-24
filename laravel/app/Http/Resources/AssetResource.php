<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PlantResource;
use App\Http\Resources\AssetTypeResource;
use App\Http\Resources\VoltageResource;
use App\Http\Resources\WattRatingResource;
use App\Http\Resources\FrameResource;
use App\Http\Resources\MountingResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\MakeResource;
use App\Http\Resources\SpeedResource;
use App\Models\AssetParameter;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_parameters = AssetParameter::whereHas('AssetParameterTypes', function($que){
            $que->where('asset_type_id', $this->asset_type_id);
        })->get();
        return [
            'asset_id' => $this->asset_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'asset_code' => $this->asset_code,
            'asset_name' => $this->asset_name,
            'asset_type_id' => $this->asset_type_id,
            'asset_type' => new AssetTypeResource($this->AssetType),
            // 'asset_parameter_values' => AssetParameterResource::collection($this->AssetParameters),
            'status' => $this->deleted_at?false:true,
            'asset_parameters' => AssetAttributeVResource::collection($asset_parameters->map(function ($assetParameter) {
                return ['resource' => $assetParameter, 'asset_id' => $this->asset_id];
            })),
            // 'asset_parameters' => $asset_parameters,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'department_id' => $this->department_id,
            'department' => new DepartmentResource($this->Department),
            'section_id' => $this->section_id,
            'section' => new SectionResource($this->Section),
            'radius' => $this->radius
        ];
    }
}
