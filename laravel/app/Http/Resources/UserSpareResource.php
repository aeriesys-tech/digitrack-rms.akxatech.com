<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSpareResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_spare_id' => $this->user_spare_id,
            'user_service_id' => $this->user_service_id,
            'spare_id' => $this->spare_id,
            'spare' => new SpareResource($this->Spare),
            'spare_cost' => $this->spare_cost,
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($this->AssetZone),
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->Service),
            'service_cost' => $this->service_cost,
            'quantity' => $this->quantity
        ];
    }
}
