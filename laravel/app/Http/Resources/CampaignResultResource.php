<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'campaign_result_id' => $this->campaign_result_id,
            'campaign_id' => $this->campaign_id,
            'campaign' => new CampaignResource($this->Campaign),
            'asset_id' => $this->asset_id,
            'asset' => new AssetResource($this->Asset),
            'location' => $this->location,
            'date' => $this->date,
            'file' => $this->file ? config('app.asset_url').'campaigns/'.$this->file : null,
            'status' => $this->deleted_at?false:true
        ];
    }
}
