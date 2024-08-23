<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakDownListAssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'break_down_list_asset_type_id' => $this->break_down_list_asset_type_id,
            'asset_type_id' => $this->asset_type_id,
            'asset_types' => new AssetTypeResource($this->AssetType),
            'break_down_list_id' => $this->break_down_list_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
