<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class UserServicePendingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $date1 = Carbon::parse($this->next_service_date)->startOfDay();
        $date2 = Carbon::now()->startOfDay();
        $daysCount = $date1->greaterThan($date2) ? $date1->diffInDays($date2) - 1 : $date1->diffInDays($date2);
        
        return [
            'user_service_id' => $this->user_service_id,
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'service_id' => $this->service_id,
            'service' => new ServiceResource($this->Service),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->User),
            'service_no' => $this->service_no,
            'service_cost' => $this->service_cost,
            'user_spares' => UserSpareResource::collection($this->UserSpare),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'service_date' => $this->service_date,
            'next_service_date' => $this->next_service_date,
            'delay_days' => round($daysCount),
            'note' => $this->note,
            'is_latest' => $this->is_latest
        ];
    }
}
