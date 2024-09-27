<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVariableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $groupedUserAssetVariables = $this->UserAssetVariable->groupBy('asset_zone_id')->map(function ($group) {
            return $group->map(function ($variable) {
                return [
                    'user_asset_variable_id' => $variable->user_asset_variable_id,
                    'user_variable_id' => $variable->user_variable_id,
                    'variable_id' => $variable->variable_id,
                    'variable' => new VariableResource($variable->Variable),
                    'asset_zone_id' => $variable->asset_zone_id,
                    'asset_zone' => new AssetZoneResource($variable->AssetZone),
                    'value' => $variable->value,
                ];
            });
        })->values();

        return [
            'user_variable_id' => $this->user_variable_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'user_id' => $this->user_id,
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'job_date' => $this->job_date,
            'job_no' => $this->job_no,
            'note' => $this->note,
            'user_asset_variables' => $groupedUserAssetVariables 
        ];
    }
}

