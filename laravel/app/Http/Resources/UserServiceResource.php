<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserServiceResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'user_service_id' => $this->user_service_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            // 'service_id' => $this->service_id,
            // 'service' => new ServiceResource($this->Service),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->User),
            'service_no' => $this->service_no,
            // 'service_cost' => $this->service_cost,
            'user_spares' => UserSpareResource::collection($this->UserSpare),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            // 'asset_zone_id' => $this->asset_zone_id,
            // 'asset_zone' => new AssetZoneResource($this->AssetZone),
            'service_date' => $this->service_date,
            'next_service_date' => $this->next_service_date,
            'note' => $this->note,
            'is_latest' => $this->is_latest
        ];
    }
}
