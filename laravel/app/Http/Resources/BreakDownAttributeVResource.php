<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BreakDownAttributeValue;

class BreakDownAttributeVResource extends JsonResource
{
    protected $breakDownId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->breakDownId = $resource['break_down_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $break_down_attribute_value = BreakDownAttributeValue::where('break_down_id', $this->breakDownId)
            ->where('break_down_attribute_id', $this->break_down_attribute_id)
            ->first();

        return [
            'break_down_attribute_id' => $this->break_down_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'break_down_attribute_value' => $break_down_attribute_value
        ];
    }
}
