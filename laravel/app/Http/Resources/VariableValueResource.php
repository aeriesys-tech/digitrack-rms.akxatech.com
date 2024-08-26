<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\VariableAttributeValue;

class VariableValueResource extends JsonResource
{
    protected $variableId;

    public function __construct($resource)
    {
        parent::__construct($resource['resource']);
        $this->variableId = $resource['variable_id'] ?? null;
    }

    public function toArray(Request $request): array
    {
        $variable_attribute_value = VariableAttributeValue::where('variable_id', $this->variableId)
            ->where('variable_attribute_id', $this->variable_attribute_id)->first();

        return [
            'variable_attribute_id' => $this->variable_attribute_id,
        	'field_name' => $this->field_name,
	        'display_name' => $this->display_name,
	        'field_type' => $this->field_type, 
	        'field_values' => $this->field_values,
	        'field_length' => $this->field_length,
	        'is_required' => $this->is_required? 1 :0,
	        'user_id' => $this->user_id,
            'status' => $this->deleted_at?false:true,
            'variable_attribute_value' => $variable_attribute_value
        ];
    }
}
