<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetDataSourceResource;
use App\Models\AssetDataSource;
use App\Models\DataSource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DataSourceResource;
use App\Models\Asset;
use App\Models\AssetZone;
use App\Models\DataSourceAttributeValue;
use App\Http\Resources\DataSourceAttributeValueResource;
use App\Models\AssetDataSourceValue;

class AssetDataSourceController extends Controller
{
    public function paginateAssetDataSources(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetDataSource::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->data_source_id))
        {
            $query->where('data_source_id',$request->data_source_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('Asset', function($query) use($request){
                $query->where('asset_name', 'like', "$request->search%");
            })->orWherehas('DataSource', function($query) use($request){
                $query->where('data_source_name', 'like', "$request->search%");
            });
        }
        $asset_data_source = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 

        //DropDown DataSources
        $data_source = DataSource::whereHas('DataSourceAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();

        return response()->json([
            'paginate_data_sources' => AssetDataSourceResource::collection($asset_data_source),
            'meta' => [
                'current_page' => $asset_data_source->currentPage(),
                'last_page' => $asset_data_source->lastPage(),
                'per_page' => $asset_data_source->perPage(),
                'total' => $asset_data_source->total(),
            ],
            'data_sources' => DataSourceResource::collection($data_source)
        ]);
    }

    public function getAssetDataSources()
    {
        $asset_data_source = AssetDataSource::all();
        return AssetDataSourceResource::collection($asset_data_source);
    }

    public function addAssetDataSource(Request $request)
    {
        // $userPlantId = Auth::User()->plant_id;
        // $areaId = Auth::User()->Plant->area_id;
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            'data_source_id' => [
                'required',
                'exists:data_sources,data_source_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetDataSource::where('data_source_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('data_source_asset_zones')) {
                                $query->whereIn('asset_zone_id', $request->data_source_asset_zones);
                            } else {
                                $query->whereNull('asset_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('data_source_asset_zones') && $assetHasZones) {
                            $fail('The combination of DataSource and Asset Zone already exists.');
                        } else {
                            $fail('The combination of DataSource and Asset already exists.');
                        }
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'data_source_asset_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id',
            'script' => 'required'
        ]);

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source_type = $data_source->data_source_type_id;
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['data_source_type_id'] = $data_source_type;

        $createdDataSources = [];

        if (!empty($data['data_source_asset_zones'])) {
            foreach ($data['data_source_asset_zones'] as $zoneId) {
                if (is_null($zoneId) || $zoneId == 0) {
                    continue;
                }

                $data_source_data = $data;
                $data_source_data['asset_zone_id'] = $zoneId;

                $assetDataSource = AssetDataSource::create($data_source_data);
                $createdDataSources[] = new AssetDataSourceResource($assetDataSource);

                foreach($request->asset_datasource_attributes as $attribute)
                {
                    AssetDataSourceValue::create([
                        'asset_data_source_id' => $assetDataSource->asset_data_source_id,
                        'asset_id' => $assetDataSource->asset_id,
                        'data_source_id' => $data_source->data_source_id,
                        'asset_zone_id' => $assetDataSource->asset_zone_id,
                        'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                        'field_value' => $attribute['field_value'] ?? ''
                    ]);
                }
            }
        } else {
            $data_source_data = $data;
            $data_source_data['asset_zone_id'] = null;

            $assetDataSource = AssetDataSource::create($data_source_data);
            $createdDataSources[] = new AssetDataSourceResource($assetDataSource);

            foreach($request->asset_datasource_attributes as $attribute)
            {
                AssetDataSourceValue::create([
                    'asset_data_source_id' => $assetDataSource->asset_data_source_id,
                    'asset_id' => $assetDataSource->asset_id,
                    'data_source_id' => $data_source->data_source_id,
                    'asset_zone_id' => $assetDataSource->asset_zone_id,
                    'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                    'field_value' => $attribute['field_value'] ?? ''
                ]);
            }
        }

        return response()->json([$createdDataSources,  "message" => "AssetDataSource Created Successfully"]);
    }

    public function getAssetDataSource(Request $request)
    {
        $request->validate([
            'asset_data_source_id' => 'required|exists:asset_data_sources,asset_data_source_id'
        ]);

        $asset_data_source = AssetDataSource::where('asset_data_source_id',$request->asset_data_source_id)->first();
        return new AssetDataSourceResource($asset_data_source);
    }

    public function updateAssetDataSource(Request $request)
    {
        $asset_data_sources = AssetDataSource::where('asset_data_source_id', $request->asset_data_source_id)->first();
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $data = $request->validate([
            'asset_data_source_id' => 'required|exists:asset_data_sources,asset_data_source_id',
            'data_source_id' => [
                'required',
                'exists:data_sources,data_source_id',
                function ($attribute, $value, $fail) use ($request, $asset_data_sources) 
                {
                    if ($value != $asset_data_sources->data_source_id) {
                        $exists = AssetDataSource::where('data_source_id', $value)
                            ->where('asset_id', $request->asset_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('asset_zone_id')) {
                                    $query->where('asset_zone_id', $request->asset_zone_id);
                                } else {
                                    $query->whereNull('asset_zone_id');
                                }
                            })->where('asset_data_source_id', '!=', $request->asset_data_source_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of DataSource, Asset, and Asset Zone already exists.');
                        }
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
            'script' => 'required'
        ]);

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source_type = $data_source->data_source_type_id;
        $asset = Asset::where('asset_id', $request->asset_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        $data['data_source_type_id'] = $data_source_type;

        $asset_data_source = AssetDataSource::where('asset_data_source_id', $request->asset_data_source_id)->first();
        $asset_data_source->update($data);

        if(isset($request->deleted_asset_datasource_values)>0)
        {
            AssetDataSourceValue::whereIn('asset_data_source_value_id', $request->deleted_asset_datasource_values)->forceDelete();
        }

        foreach ($request->asset_datasource_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) 
            {
                AssetDataSourceValue::updateOrCreate(
                    [
                        'asset_data_source_id' => $asset_data_source->asset_data_source_id,
                        'asset_zone_id' => $asset_data_source->asset_zone_id,
                        'data_source_id' => $data_source->data_source_id,
                        'asset_id' =>  $asset_data_source->asset_id,
                        'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json([
            "message" => "AssetDataSource Updated Successfully",
            new AssetDataSourceResource($asset_data_source)
        ]); 
    }

    public function deleteAssetDataSource(Request $request)
    {
        $request->validate([
            'asset_data_source_id' => 'required|exists:asset_data_sources,asset_data_source_id'
        ]);

        AssetDataSource::where('asset_data_source_id', $request->asset_data_source_id)->delete();

        return response()->json([
            'message' => "AssetDataSource Deleted Successfully"
        ]);
    }

    public function getAssetTypeDataSources(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $data_source = DataSource::whereHas('DataSourceAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return DataSourceResource::collection($data_source);
    }

    public function assetDataSourceScripts(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);

        $scripts = AssetDataSource::where('asset_id', $request->asset_id)->pluck('script')->unique();
        return $scripts;
    }

    public function assetDataSourceAttributeValues(Request $request)
    {
        $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id'
        ]);

        $datasource_attribute_values = DataSourceAttributeValue::where('data_source_id', $request->data_source_id)->get();
        return DataSourceAttributeValueResource::collection($datasource_attribute_values);
    }
}
