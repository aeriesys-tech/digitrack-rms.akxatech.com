<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ServiceAttributeValue;

class ServiceValueResource extends JsonResource
{
    protected $serviceId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->serviceId = $resource['service_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $service_attribute_value = ServiceAttributeValue::where('service_id', $this->serviceId)
            ->where('service_attribute_id', $this->service_attribute_id)->first();
        if(!$service_attribute_value){
            $service_attribute_value = [
                'field_value' => null
            ];
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
	        // 'asset_parameter_types' => AssetParameterTypeResource::collection($this->AssetParameterTypes),
            'status' => $this->deleted_at?false:true,
            // 'asset_types' => $asset_types,
            'service_attribute_value' => $service_attribute_value,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter)
        ];
    }
}
