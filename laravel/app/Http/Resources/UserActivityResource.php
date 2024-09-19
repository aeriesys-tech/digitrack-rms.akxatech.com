<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user_activity_id' => $this->user_activity_id,
            'activity_no' => $this->activity_no,
            'activity_date' => $this->activity_date,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->User),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'status' => $this->status,
            'activity_status' => $this->activity_status,
            'reason_id' => $this->reason_id,
            'reason' => new ReasonResource($this->Reason),
            'cost' => $this->cost,
            'note' => $this->note
        ];
    }
}
