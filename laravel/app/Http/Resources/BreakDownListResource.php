<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BreakDownAttribute;

class BreakDownListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $break_down_attributes = BreakDownAttribute::whereHas('BreakDownAttributeTypes', function($que){
            $que->where('break_down_type_id', $this->break_down_type_id);
        })->get();

        return [
            'break_down_list_id' => $this->break_down_list_id,
            'break_down_type_id' => $this->break_down_type_id,
            'break_down_type' => new BreakDownTypeResource($this->BreakDownType),
            'status' => $this->deleted_at?false:true,
            'break_down_attributes' => BreakDownValueResource::collection($break_down_attributes->map(function ($BreakDownAttribute) {
                return ['resource' => $BreakDownAttribute, 'break_down_list_id' => $this->break_down_list_id];
            })),
            'job_no' => $this->job_no,
            'job_date' => $this->job_date,
            'note' => $this->note,
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset)
        ];
    }
}
