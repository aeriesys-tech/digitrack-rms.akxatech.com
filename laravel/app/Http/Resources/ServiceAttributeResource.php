<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $service_types = [];
        foreach($this->ServiceAttributeTypes as $ServiceAttributeType)
        {
            array_push($service_types, $ServiceAttributeType['service_type_id']);
        }
        return [
            'service_attribute_id' => $this->service_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
	        'service_attribute_types' => ServiceAttributeTypeResource::collection($this->ServiceAttributeTypes),
            'status' => $this->deleted_at?false:true,
            'service_types' => $service_types,
            'service_attribute_value' => [
                "field_value" => ''
            ]
        ];
    }
}
