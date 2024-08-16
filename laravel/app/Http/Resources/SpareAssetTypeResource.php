<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpareAssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'spare_asset_type_id' => $this->spare_asset_type_id,
            'asset_type_id' => $this->asset_type_id,
            'asset_types' => new AssetTypeResource($this->AssetType),
            'spare_id' => $this->spare_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
