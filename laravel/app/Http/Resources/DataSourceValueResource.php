<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\DataSourceAttributeValue;

class DataSourceValueResource extends JsonResource
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
            ->where('data_source_attribute_id', $this->data_source_attribute_id)->first();

        return [
            'data_source_attribute_id' => $this->data_source_attribute_id,
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
            'data_source_attribute_value' => $data_source_attribute_value,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter)
        ];
    }
}
