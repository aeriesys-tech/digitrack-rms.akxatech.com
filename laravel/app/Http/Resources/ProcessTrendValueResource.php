<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\UserVariable;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessTrendValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user_variable = UserVariable::where('user_variable_id', $this->user_variable_id)->first();
        return [
            'user_asset_variable_id' => $this->user_asset_variable_id,
            'user_variable_id' => $this->user_variable_id,
            'job_date' => $user_variable->job_date,
            'variable_id' => $this->variable_id,
            'variable' => new VariableResource($this->Variable),
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($this->AssetZone),
            'value' => $this->value 
        ];
    }
}
