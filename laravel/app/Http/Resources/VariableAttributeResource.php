<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariableAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $variable_types = [];
        foreach($this->VariableAttributeTypes as $VariableAttributeType)
        {
            array_push($variable_types, $VariableAttributeType['variable_type_id']);
        }
        return [
            'variable_attribute_id' => $this->variable_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'list_parameter_id' => $this->list_parameter_id,
	        'variable_attribute_types' => VariableAttributeTypeResource::collection($this->VariableAttributeTypes),
            'status' => $this->deleted_at?false:true,
            'variable_types' => $variable_types
        ];
    }
}
