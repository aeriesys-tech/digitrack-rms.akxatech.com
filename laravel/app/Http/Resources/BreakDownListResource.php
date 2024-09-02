<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\BreakDownAttribute;

class BreakDownListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];

        foreach($this->BreakDownListAssetTypes as $BreakDownListAssetType)
        {
            array_push($asset_types, $BreakDownListAssetType['asset_type_id']);
        }

        $break_down_attributes = BreakDownAttribute::whereHas('BreakDownAttributeTypes', function($que){
            $que->where('break_down_type_id', $this->break_down_type_id)->where('field_type', '!=', "List");
        })->get();

        return [
            'break_down_list_id' => $this->break_down_list_id,
            'break_down_type_id' => $this->break_down_type_id,
            'break_down_type' => new BreakDownTypeResource($this->BreakDownType),
            'break_down_list_code' => $this->break_down_list_code,
            'break_down_list_name' => $this->break_down_list_name,
            'status' => $this->deleted_at?false:true,
            'break_down_list_asset_types' => BreakDownListAssetTypeResource::collection($this->BreakDownListAssetTypes),
            'asset_types' => $asset_types,
            'frequency_id' => $this->frequency_id,
            'frequency' => new FrequencyResource($this->Frequency),
            'break_down_attributes' => BreakDownValueResource::collection($break_down_attributes->map(function ($BreakDownAttribute) {
                return ['resource' => $BreakDownAttribute, 'break_down_list_id' => $this->break_down_list_id];
            })),
        ];
    }
}
