<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateDataSourceValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'template_datasource_value_id' => $this->template_datasource_value_id,
            'asset_template_datasource_id' => $this->asset_template_datasource_id,
            'asset_template_id' => $this->asset_template_id,
            'data_source_id' => $this->data_source_id,
            'template_zone_id' => $this->template_zone_id,
            'data_source_attribute_id' => $this->data_source_attribute_id,
            'data_source_attributes' => DataSourceAttributeResource::collection($this->DataSourceAttribute),
            'field_value' => $this->field_value
        ];
    }
}
