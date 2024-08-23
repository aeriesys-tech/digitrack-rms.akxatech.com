<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSourceAssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'data_source_asset_type_id' => $this->data_source_asset_type_id,
            'asset_type_id' => $this->asset_type_id,
            'asset_types' => new AssetTypeResource($this->AssetType),
            'data_source_id' => $this->data_source_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
