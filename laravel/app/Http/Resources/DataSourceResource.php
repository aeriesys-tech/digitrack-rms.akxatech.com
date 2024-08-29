<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\models\DataSourceAttribute;

class DataSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $asset_types = [];

        foreach($this->DataSourceAssetTypes as $DataSourceAssetType)
        {
            array_push($asset_types, $DataSourceAssetType['asset_type_id']);
        }

        $data_source_attributes = DataSourceAttribute::whereHas('DataSourceAttributeTypes', function($que){
            $que->where('data_source_type_id', $this->data_source_type_id);
        })->get();

        return [
            'data_source_id' => $this->data_source_id,
            'data_source_type_id' => $this->data_source_type_id,
            'data_source_type' => new DataSourceTypeResource($this->DataSourceType),
            'data_source_code' => $this->data_source_code,
            'data_source_name' => $this->data_source_name,
            'list_parameter_id' => $this->list_parameter_id,
            'list_parameter' => new ListParameterResource($this->ListParameter),
            'status' => $this->deleted_at?false:true,
            'data_source_asset_types' => DataSourceAssetTypeResource::collection($this->DataSourceAssetTypes),
            'asset_types' => $asset_types,
            'data_source_attributes' => DataSourceValueResource::collection($data_source_attributes->map(function ($DataSourceAttribute) {
                return ['resource' => $DataSourceAttribute, 'data_source_id' => $this->data_source_id];
            })),
        ];
    }
}