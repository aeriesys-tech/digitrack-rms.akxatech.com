<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpareAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $spare_types = [];
        foreach($this->SpareAttributeTypes as $SpareAttributeType)
        {
            array_push($spare_types, $SpareAttributeType['spare_type_id']);
        }
        return [
            'spare_attribute_id' => $this->spare_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'list_parameter_id' => $this->list_parameter_id,
	        'spare_attribute_types' => SpareAttributeTypeResource::collection($this->SpareAttributeTypes),
            'status' => $this->deleted_at?false:true,
            'spare_types' => $spare_types
        ];
    }
}
