<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceAttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data_source_attribute_value_id' => $this->data_source_attribute_value_id,
            'data_source_attribute_id' => $this->data_source_attribute_id,
            'data_source_attributes' => DataSourceAttributeResource::collection($this->DataSourceAttribute),
            'data_source_id' => $this->data_source_id,
            'field_value' => $this->field_value,
            'status' => $this->deleted_at?false:true
        ];
    }
}
