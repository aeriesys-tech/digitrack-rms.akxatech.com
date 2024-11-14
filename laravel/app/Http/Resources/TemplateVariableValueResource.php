<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateVariableValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template_variable_value_id' => $this->template_variable_value_id,
            'asset_template_variable_id' => $this->asset_template_variable_id,
            'asset_template_id' => $this->asset_template_id,
            'variable_id' => $this->variable_id,
            'template_zone_id' => $this->template_zone_id,
            'variable_attribute_id' => $this->variable_attribute_id,
            'variable_attributes' => VariableAttributeResource::collection($this->VariableAttribute),
            'field_value' => $this->field_value
        ];
    }
}
