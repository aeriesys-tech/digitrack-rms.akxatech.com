<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\AssetSpare;

class AssetZoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_Spares = AssetSpare::with(['Area', 'Spare', 'Asset', 'Plant', 'SpareType', 'AssetSpareValue'])
        ->where('asset_zone_id', $this->asset_zone_id)->where('asset_id', $this->asset_id)->get();

        return [
            'asset_zone_id' => $this->asset_zone_id,
            'asset_id' => $this->asset_id,
            // 'asset' => new AssetResource($this->Asset),
            'zone_name' => $this->zone_name,
            'status' => $this->deleted_at?false:true,
            'height' => $this->height,
            'diameter' => $this->diameter,
            'asset_spares' => $asset_Spares,
        ];
    }
}
