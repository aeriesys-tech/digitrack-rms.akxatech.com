<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->CheckAssetTypes as $CheckAssetType)
        {
            array_push($asset_types, $CheckAssetType['asset_type_id']);
        }
        return [
            'check_id' => $this->check_id,
            'field_name' => $this->field_name,
            'field_type' => $this->field_type,
            'default_value' => $this->default_value,
            'is_required' => $this->is_required,
            'lcl' => $this->lcl,
            'ucl' => $this->ucl,
            'field_values' => $this->field_values,
            'order' => $this->order,
            'status' => $this->deleted_at?false:true,
            'check_asset_types' => CheckAssetTypeResource::collection($this->CheckAssetTypes),
            'asset_types' => $asset_types,
            // 'list_parameter_id' => $this->list_parameter_id,
            // 'list_parameter' => new ListParameterResource($this->ListParameter)
        ];
    }
}
