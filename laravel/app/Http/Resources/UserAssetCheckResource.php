<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAssetCheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_asset_check_id' => $this->user_asset_check_id,
            'check_id' => $this->check_id,
            'user_check_id' => $this->user_check_id,
            'asset_check_id' => $this->asset_check_id,
            'field_name' => $this->field_name,
            'field_type' => $this->field_type,
            'default_value' => $this->default_value,
            'is_required' => $this->is_required,
            'lcl' => $this->lcl,
            'ucl' => $this->ucl,
            'field_values' => $this->field_values,
            'order' => $this->order,
            'value' => $this->value
        ];
    }
}
