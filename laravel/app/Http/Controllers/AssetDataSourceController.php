<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetDataSourceResource;
use App\Models\AssetDataSource;
use App\Models\DataSource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DataSourceResource;

class AssetDataSourceController extends Controller
{
    public function paginateAssetDataSources(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
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
        return AssetDataSourceResource::collection($asset_data_source);
    }

    public function getAssetDataSources()
    {
        $asset_data_source = AssetDataSource::all();
        return AssetDataSourceResource::collection($asset_data_source);
    }

    public function addAssetDataSource(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;

        $data = $request->validate([
            'data_source_id' => [
                'required',
                'exists:data_sources,data_source_id',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = AssetDataSource::where('data_source_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->where(function ($query) use ($request) {
                            if ($request->has('asset_zones')) {
                                $query->whereIn('asset_zone_id', $request->asset_zones);
                            } else {
                                $query->whereNull('asset_zone_id');
                            }
                        })->exists();
    
                    if ($exists) {
                        $fail('The combination of DataSource, Asset, and Asset Zone already exists.');
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zones' => 'nullable|array',
            'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id'
        ]);

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source_type = $data_source->data_source_type_id;

        $data['plant_id'] = $userPlantId;
        $data['area_id'] = $areaId;
        $data['data_source_type_id'] = $data_source_type;

        $createdDataSources = [];

        if (!empty($data['asset_zones'])) {
            foreach ($data['asset_zones'] as $zoneId) {
                if (is_null($zoneId) || $zoneId == 0) {
                    continue;
                }

                $data_source_data = $data;
                $data_source_data['asset_zone_id'] = $zoneId;

                $assetDataSource = AssetDataSource::create($data_source_data);
                $createdDataSources[] = new AssetDataSourceResource($assetDataSource);
            }
        } else {
            $data_source_data = $data;
            $data_source_data['asset_zone_id'] = null;

            $assetDataSource = AssetDataSource::create($data_source_data);
            $createdDataSources[] = new AssetDataSourceResource($assetDataSource);
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
        $userPlantId = Auth::User()->plant_id;
        $areaId = Auth::User()->Plant->area_id;

        $asset_data_source = AssetDataSource::where('asset_data_source_id', $request->asset_data_source_id)->first();

        $data = $request->validate([
            'asset_data_source_id' => 'required|exists:asset_data_sources,asset_data_source_id',
            'data_source_id' => [
            'required',
            'exists:data_sources,data_source_id',
                function ($attribute, $value, $fail) use ($request, $asset_data_source) {
                    $exists = AssetDataSource::where('data_source_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->where(function ($query) use ($request) {
                            if ($request->filled('asset_zone_id')) {
                                $query->where('asset_zone_id', $request->asset_zone_id);
                            } else {
                                $query->whereNull('asset_zone_id');
                            }
                        })
                        ->where('asset_data_source_id', '!=', $request->asset_data_source_id)->exists();

                    if ($exists) {
                        $fail('The combination of DataSource, Asset, and Asset Zone already exists.');
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zones' => 'nullable|array',
            'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
        ]);

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source_type = $data_source->data_source_type_id;

        $data['plant_id'] = $userPlantId;
        $data['data_source_type_id'] = $data_source_type;
        $data['area_id'] = $areaId;

        $asset_data_source = AssetDataSource::where('asset_data_source_id', $request->asset_data_source_id)->first();
        $asset_data_source->update($data);
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
}
