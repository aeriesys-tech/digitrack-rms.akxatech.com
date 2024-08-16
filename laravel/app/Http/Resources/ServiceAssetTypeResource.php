<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'service_asset_type_id' => $this->service_asset_type_id,
            'asset_type_id' => $this->asset_type_id,
            'asset_types' => new AssetTypeResource($this->AssetType),
            'service_id' => $this->service_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
