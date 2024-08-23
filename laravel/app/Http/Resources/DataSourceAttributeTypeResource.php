<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data_source_attribute_type_id' => $this->data_source_attribute_type_id,
            'data_source_attribute_id' => $this->data_source_attribute_id,
            'data_source_type_id' => $this->data_source_type_id,
            'data_source_type' => new DataSourceTypeResource($this->DataSourceType),
            'status' => $this->deleted_at?false:true
        ];
    }
}
