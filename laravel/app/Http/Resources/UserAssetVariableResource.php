<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAssetVariableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_asset_variable_id' => $this->user_asset_variable_id,
            'user_variable_id' => $this->user_variable_id,
            'variable_id' => $this->variable_id,
            'asset_variable_id' => $this->asset_variable_id,
            'date_time' => $this->date_time,
            'value' => $this->value 
        ];
    }
}
