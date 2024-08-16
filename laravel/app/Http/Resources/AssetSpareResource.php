<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SpareResource;
use App\Http\Resources\AssetResource;
use App\Http\Resources\PlantResource;

class AssetSpareResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'asset_spare_id' => $this->asset_spare_id,
            'spare_id' => $this->spare_id,
            'spare' => new SpareResource($this->Spare),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'plant_id' => $this->plant_id,
            'plant' => new PlantResource($this->Plant),
            'status' => $this->deleted_at?false:true
        ];
    }
}
