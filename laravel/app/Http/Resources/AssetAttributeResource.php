<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->AssetAttributeTypes as $AssetAttributeType)
        {
            array_push($asset_types, $AssetAttributeType['asset_type_id']);
        }
        return [
            'asset_attribute_id' => $this->asset_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
	        'asset_attribute_types' => AssetAttributeTypeResource::collection($this->AssetAttributeTypes),
            'status' => $this->deleted_at?false:true,
            'asset_types' => $asset_types
        ];
    }
}
