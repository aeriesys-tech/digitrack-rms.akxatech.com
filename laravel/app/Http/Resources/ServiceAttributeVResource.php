<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ServiceAttributeValue;

class ServiceAttributeVResource extends JsonResource
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
            ->where('service_attribute_id', $this->service_attribute_id)
            ->first();

        return [
            'service_attribute_id' => $this->service_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'service_attribute_value' => $service_attribute_value
        ];
    }
}
