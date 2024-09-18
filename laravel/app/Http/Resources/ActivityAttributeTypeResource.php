<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityAttributeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'activity_attribute_type_id' => $this->activity_attribute_type_id,
            'reason_id' => $this->reason_id,
            'activity_type' => new ReasonResource($this->Reason),
            'activity_attribute_id' => $this->activity_attribute_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
