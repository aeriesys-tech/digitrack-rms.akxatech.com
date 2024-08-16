<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetParameterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->AssetParameterTypes as $AssetParameterType)
        {
            array_push($asset_types, $AssetParameterType['asset_type_id']);
        }
        return [
            'asset_parameter_id' => $this->asset_parameter_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
	        'asset_parameter_types' => AssetParameterTypeResource::collection($this->AssetParameterTypes),
            'status' => $this->deleted_at?false:true,
            'asset_types' => $asset_types
        ];
    }
}
