<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserVariableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_variable_id' => $this->user_variable_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'user_id' => $this->user_id,
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'job_date' => $this->job_date,
            'note' => $this->note,
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($this->AssetZone),
            'asset_variables' => UserAssetVariableResource::collection($this->UserAssetVariable),
        ];
    }
}
