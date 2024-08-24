<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceAttributeVResource extends JsonResource
{
    protected $dataSourceId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->dataSourceId = $resource['data_source_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $data_source_attribute_value = DataSourceAttributeValue::where('data_source_id', $this->dataSourceId)
            ->where('data_source_attribute_id', $this->data_source_attribute_id)
            ->first();

        return [
            'data_source_attribute_id' => $this->data_source_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'data_source_attribute_value' => $data_source_attribute_value
        ];
    }
}
