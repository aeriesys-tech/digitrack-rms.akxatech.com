<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Spare;
use App\Models\AssetZone;
use App\Models\UserService;
use App\Models\Service;

class TotalQuantitySpareResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Fetch related models
        $spare = Spare::where('spare_id', $this->spare_id)->first();
        $userService = UserService::where('user_service_id', $this->user_service_id)->first();
        $assetZone = AssetZone::where('asset_zone_id', $this->asset_zone_id)->first();
        $service = Service::where('service_id', $this->service_id)->first();

        return [
            'user_spare_id' => $this->user_spare_id,
            'user_service_id' => $this->user_service_id,
            'spare_id' => $this->spare_id,
            'spare' => new SpareResource($spare),
            'spare_cost' => $this->spare_cost,
            'asset_zone_id' => $this->asset_zone_id,
            'asset_zone' => new AssetZoneResource($assetZone),
            'service_id' => $this->service_id,
            'asset_type_name' => optional($userService->Asset->AssetType)->asset_type_name ?? null,
            'service' => new ServiceResource($service),
            'service_cost' => $this->service_cost,
            'total_quantity' => $this->quantity ?? null,
            'asset_name' => optional($userService->Asset)->asset_name ?? null
        ];
    }
}
