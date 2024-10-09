<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceAttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data_source_types = [];
        foreach($this->DataSourceAttributeTypes as $DataSourceAttributeType)
        {
            array_push($data_source_types, $DataSourceAttributeType['data_source_type_id']);
        }
        return [
            'data_source_attribute_id' => $this->data_source_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
	        'data_source_attribute_types' => DataSourceAttributeTypeResource::collection($this->DataSourceAttributeTypes),
            'status' => $this->deleted_at?false:true,
            'data_source_types' => $data_source_types,
            'data_source_attribute_value' => [
                "field_value" => ''
            ]
        ];
    }
}
