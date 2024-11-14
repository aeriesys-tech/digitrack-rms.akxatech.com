<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\AssetTemplateSpare;

class TemplateZoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_Spares = AssetTemplateSpare::with(['Area', 'Spare', 'AssetTemplate', 'Plant', 'SpareType', 'TemplateSpareValue'])
        ->where('template_zone_id', $this->template_zone_id)->where('asset_template_id', $this->asset_template_id)->get();

        return [
            'template_zone_id' => $this->template_zone_id,
            'asset_template_id' => $this->asset_template_id,
            // 'asset' => new AssetResource($this->Asset),
            'zone_name' => $this->zone_name,
            'height' => $this->height,
            'diameter' => $this->diameter,
            'asset_spares' => $asset_Spares,
        ];
    }
}
