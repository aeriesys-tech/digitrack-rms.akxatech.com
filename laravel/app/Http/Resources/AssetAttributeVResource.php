<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\AssetAttributeValue;

class AssetAttributeVResource extends JsonResource
{
    protected $assetId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->assetId = $resource['asset_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $asset_attribute_value = AssetAttributeValue::where('asset_id', $this->assetId)
            ->where('asset_attribute_id', $this->asset_attribute_id)
            ->first();

        return [
            'asset_attribute_id' => $this->asset_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
	        // 'asset_parameter_types' => AssetParameterTypeResource::collection($this->AssetParameterTypes),
            'status' => $this->deleted_at?false:true,
            // 'asset_types' => $asset_types,
            'asset_attribute_value' => $asset_attribute_value
        ];
    }
}
