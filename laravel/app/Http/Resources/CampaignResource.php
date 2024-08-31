<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Asset;

class CampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset = Asset::where('asset_id',$this->asset_id)->first();
        return [
            'campaign_id' => $this->campaign_id,
            'asset_id' => $this->asset_id,
            'asset' => $asset,
            'datasource' => $this->datasource,
            'file' => $this->file ? config('app.asset_url').'campaigns/'.$this->file : null,
            'status' => $this->deleted_at?false:true
        ];
    }
}
