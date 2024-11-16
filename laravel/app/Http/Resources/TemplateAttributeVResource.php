<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TemplateAttributeValue;
use App\Models\AssetAttribute;
use App\Models\ListParameter;

class TemplateAttributeVResource extends JsonResource
{
    protected $assetTemplateId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->assetTemplateId = $resource['asset_template_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $asset_attribute_value = TemplateAttributeValue::where('asset_template_id', $this->assetTemplateId)
            ->where('asset_attribute_id', $this->asset_attribute_id)
            ->first();

        if(!$asset_attribute_value){
            $asset_attribute_value = [
                'field_value' => null
            ];
        }

        $assetAttribute = AssetAttribute::where('field_type', $this->field_type)->where('asset_attribute_id',$this->asset_attribute_id)->whereHas('ListParameter', function($query) {
            $query->where('field_type', 'List');
        })->first();
        $lists = $assetAttribute ? $assetAttribute->ListParameter : null;

        return [
            'asset_attribute_id' => $this->asset_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'asset_attribute_value' => $asset_attribute_value,
            'list_parameter' => $lists
        ];
    }
}
