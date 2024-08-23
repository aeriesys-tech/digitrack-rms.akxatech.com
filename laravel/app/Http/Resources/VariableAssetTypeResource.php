<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariableAssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'variable_asset_type_id' => $this->variable_asset_type_id,
            'asset_type_id' => $this->asset_type_id,
            'asset_types' => new AssetTypeResource($this->AssetType),
            'variable_id' => $this->variable_id,
            'status' => $this->deleted_at?false:true
        ];
    }
}
