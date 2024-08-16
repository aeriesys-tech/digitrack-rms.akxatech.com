<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->ServiceAssetTypes as $ServiceAssetType)
        {
            array_push($asset_types, $ServiceAssetType['asset_type_id']);
        }
        return [
            'service_id' => $this->service_id,
            'service_type_id' => $this->service_type_id,
            'service_type' => new ServiceTypeResource($this->ServiceType),
            'service_code' => $this->service_code,
            'service_name' => $this->service_name,
            'status' => $this->deleted_at?false:true,
            'service_asset_types' => ServiceAssetTypeResource::collection($this->ServiceAssetTypes),
            'asset_types' => $asset_types,
            'frequency_id' => $this->frequency_id,
            'frequency' => new FrequencyResource($this->Frequency)
        ];
    }
}
