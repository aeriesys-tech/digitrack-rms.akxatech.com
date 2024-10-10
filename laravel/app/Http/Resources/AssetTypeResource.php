<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_type_id' => $this->asset_type_id,
            'asset_type_code' => $this->asset_type_code,
            'asset_type_name' => $this->asset_type_name,
            'geometry_type' => $this->geometry_type ?? '',
            'status' => $this->deleted_at?false:true
        ];
    }
}
