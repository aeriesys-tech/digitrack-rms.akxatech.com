<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckAssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'check_asset_type_id' => $this->check_asset_type_id,
            'asset_type_id' => $this->asset_type_id,
            'asset_types' => new AssetTypeResource($this->AssetType),
            'check_id' => $this->check_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
