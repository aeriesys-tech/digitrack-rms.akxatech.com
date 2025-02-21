<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SpareAttributeValue;

class SpareAttributeVResource extends JsonResource
{
    protected $spareId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->spareId = $resource['spare_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $spare_attribute_value = SpareAttributeValue::where('spare_id', $this->spareId)
            ->where('spare_attribute_id', $this->spare_attribute_id)
            ->first();

        return [
            'spare_attribute_id' => $this->spare_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'spare_attribute_value' => $spare_attribute_value
        ];
    }
}
