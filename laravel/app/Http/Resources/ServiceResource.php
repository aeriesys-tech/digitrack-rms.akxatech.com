<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ServiceAttribute;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->ServiceAssetTypes as $ServiceAssetType)
        {
            array_push($asset_types, $ServiceAssetType['asset_type_id']);
        }
        
        $service_attributes = ServiceAttribute::whereHas('ServiceAttributeTypes', function($que){
            $que->where('service_type_id', $this->service_type_id)->where('field_type', '!=', "List");
        })->get();

        return [
            'service_id' => $this->service_id,
            'service_type_id' => $this->service_type_id,
            'service_type' => new ServiceTypeResource($this->ServiceType),
            'service_code' => $this->service_code,
            'service_name' => $this->service_name,
            // 'list_parameter_id' => $this->list_parameter_id,
            // 'list_parameter' => new ListParameterResource($this->ListParameter),
            'status' => $this->deleted_at?false:true,
            'service_asset_types' => ServiceAssetTypeResource::collection($this->ServiceAssetTypes),
            'asset_types' => $asset_types,
            'service_attributes' => ServiceValueResource::collection($service_attributes->map(function ($serviceAttribute) {
                return ['resource' => $serviceAttribute, 'service_id' => $this->service_id];
            })),
        ];
    }
}
