<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakDownAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $break_down_types = [];
        foreach($this->BreakDownAttributeTypes as $BreakDownAttributeType)
        {
            array_push($break_down_types, $BreakDownAttributeType['break_down_type_id']);
        }
        return [
            'break_down_attribute_id' => $this->break_down_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
	        'break_down_attribute_types' => BreakDownAttributeTypeResource::collection($this->BreakDownAttributeTypes),
            'status' => $this->deleted_at?false:true,
            'break_down_types' => $break_down_types,
            'break_down_attribute_value' => [
                "field_value" => null
            ]
        ];
    }
}
