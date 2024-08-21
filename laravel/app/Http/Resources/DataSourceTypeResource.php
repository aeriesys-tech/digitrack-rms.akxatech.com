<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data_source_type_id' => $this->data_source_type_id,
            'data_source_type_code' => $this->data_source_type_code,
            'data_source_type_name' => $this->data_source_type_name,
            'status' => $this->deleted_at?false:true
        ];
    }
}