<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSource;
use App\Models\DataSourceAssetType;
use App\Http\Resources\DataSourceResource;
use App\Models\getDataSourcesDropdown;
use App\Models\DataSourceAttribute;
use App\Http\Resources\DataSourceAttributeResource;
use Auth;
use App\Models\DataSourceAttributeValue;

class DataSourceController extends Controller
{
    public function paginateDataSources(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = DataSource::query();

        if(isset($request->data_source_code))
        {
            $query->where('data_source_code',$request->data_source_code);
        }
        if(isset($request->data_source_name))
        {
            $query->where('data_source_name',$request->data_source_name);
        }
              
        if($request->search!='')
        {
            $query->where('data_source_code', 'like', "%$request->search%")
                ->orWhere('data_source_name', 'like', "$request->search%");
        }
        $data_source = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return DataSourceResource::collection($data_source);
    }

    public function getDataSources()
    {
        $data_source = DataSource::all();
        return DataSourceResource::collection($data_source);
    }

    public function addDataSource(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'data_source_code' => 'required|string|unique:data_sources,data_source_code',
            'data_source_name' => 'required|string|unique:data_sources,data_source_name',
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id',
            'data_source_attributes' => 'required|array',
            'data_source_attributes.*.data_source_attribute_id' => 'required|exists:data_source_attributes,data_source_attribute_id',
            'data_source_attributes.*.data_source_attribute_value.field_value' => 'required',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['plant_id'] = $userPlantId;

        
        $data_source = DataSource::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            DataSourceAssetType::create([
                'data_source_id' => $data_source->data_source_id,
                'asset_type_id' => $asset_type,
                ]);
        }

        foreach ($request->data_source_attributes as $attribute) 
        {
            DataSourceAttributeValue::create([
                'data_source_id' => $data_source->data_source_id,
                'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                'field_value' => $attribute['data_source_attribute_value']['field_value'] ?? '',
            ]);
        }
          
        return response()->json(["message" => "DataSource Created Successfully"]);
    }

    public function getDataSource(Request $request)
    {
        $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id'
        ]);

        $data_source = DataSource::where('data_source_id',$request->data_source_id)->first();
        return new DataSourceResource($data_source);
    }

    public function getDataSourceData(Request $request)
    {
        $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id'
        ]);

        $data_source = DataSource::where('data_source_id',$request->data_source_id)->first();
        return new DataSourceResource($data_source);
    }

    public function getAssetTypeDataSources(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $data_sources = DataSource::whereHas('DataSourceAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return DataSourceResource::collection($data_sources);
    }

    public function updateDataSource(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id',
            'data_source_code' => 'required|string|unique:data_sources,data_source_code,' . $request->data_source_id . ',data_source_id',
            'data_source_name' => 'required|string|unique:data_sources,data_source_name,' . $request->data_source_id . ',data_source_id',
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id',
            'data_source_attributes' => 'required|array',
            'data_source_attributes.*.data_source_attribute_id' => 'required|exists:data_source_attributes,data_source_attribute_id',
            'data_source_attributes.*.data_source_attribute_value.field_value' => 'required',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'deleted_data_source_attribute_values' => 'nullable'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source->update($data);

        DataSourceAssetType::where('data_source_id', $data_source->data_source_id)->delete();

        foreach ($data['asset_types'] as $asset_type_id) {
            DataSourceAssetType::create([
                'data_source_id' => $data_source->data_source_id,
                'asset_type_id' => $asset_type_id
            ]);
        }

        if($request->deleted_data_source_attribute_values > 0)
        {
            DataSourceAttributeValue::whereIn('data_source_attribute_value_id', $request->deleted_data_source_attribute_values)->forceDelete();
        }
    
        foreach ($request->data_source_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['data_source_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                DataSourceAttributeValue::updateOrCreate(
                    [
                        'data_source_id' => $data_source->data_source_id,
                        'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json(["message" => "DataSource Updated Successfully"]);
    }  

    public function deleteDataSource(Request $request)
    {
        $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id'
        ]);
        $data_source = DataSource::withTrashed()->where('data_source_id', $request->data_source_id)->first();

        if($data_source->trashed())
        {
            $data_source->restore();
            return response()->json([
                "message" => "DataSource Activated successfully"
            ],200);
        }
        else
        {
            $data_source->delete();
            return response()->json([
                "message" => "DataSource Deactivated successfully"
            ], 200);
        }
    }

    public function getDataSourcesDropdown(Request $request)
    {
        $request->validate([
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id'
        ]);

        $data_source_type = DataSourceAttribute::whereHas('DataSourceAttributeTypes', function($que) use($request){
            $que->where('data_source_type_id', $request->data_source_type_id);
        })->get();

        return DataSourceAttributeResource::collection($data_source_type);
    }
}
