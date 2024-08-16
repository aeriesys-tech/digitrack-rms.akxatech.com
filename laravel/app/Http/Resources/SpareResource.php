<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpareResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->SpareAssetTypes as $SpareAssetType)
        {
            array_push($asset_types, $SpareAssetType['asset_type_id']);
        }
        return [
            'spare_id' => $this->spare_id,
            'spare_type_id' => $this->spare_type_id,
            'spare_type' => new SpareTypeResource($this->SpareType),
            'spare_code' => $this->spare_code,
            'spare_name' => $this->spare_name,
            'status' => $this->deleted_at?false:true,
            'spare_asset_types' => SpareAssetTypeResource::collection($this->SpareAssetTypes),
            'asset_types' => $asset_types
        ];
    }
}
