<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];
        foreach($this->VariableAssetTypes as $VariableAssetType)
        {
            array_push($asset_types, $VariableAssetType['asset_type_id']);
        }
        return [
            'variable_id' => $this->variable_id,
            'variable_type_id' => $this->variable_type_id,
            'variable_type' => new VariableTypeResource($this->VariableType),
            'variable_code' => $this->variable_code,
            'variable_name' => $this->variable_name,
            'status' => $this->deleted_at?false:true,
            'variable_asset_types' => VariableAssetTypeResource::collection($this->VariableAssetTypes),
            'asset_types' => $asset_types
        ];
    }
}
