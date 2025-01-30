<?php

namespace App\Http\Controllers;
use App\Models\AssetTemplateDataSource;
use App\Models\TemplateZone;
use App\Models\DataSource;
use App\Models\AssetTemplate;
use App\Models\TemplateDataSourceValue;
use App\Http\Resources\AssetTemplateDataSourceResource;
use App\Http\Resources\DataSourceResource;

use Illuminate\Http\Request;

class AssetTemplateDataSourceController extends Controller
{
    public function paginateAssetTemplateDataSources(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
        ]);

        $query = AssetTemplateDataSource::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->data_source_id))
        {
            $query->where('data_source_id',$request->data_source_id);
        }
        if(isset($request->asset_template_id))
        {
            $query->where('asset_template_id',$request->asset_template_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('AssetTemplate', function($query) use($request){
                $query->where('template_name', 'like', "$request->search%");
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
            'paginate_data_sources' => AssetTemplateDataSourceResource::collection($asset_data_source),
            'meta' => [
                'current_page' => $asset_data_source->currentPage(),
                'last_page' => $asset_data_source->lastPage(),
                'per_page' => $asset_data_source->perPage(),
                'total' => $asset_data_source->total(),
            ],
            'data_sources' => DataSourceResource::collection($data_source)
        ]);
    }

    public function addAssetTemplateDataSource(Request $request)
    {
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'data_source_id' => [
                'required',
                'exists:data_sources,data_source_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetTemplateDataSource::where('data_source_id', $value)
                        ->where('asset_template_id', $request->asset_template_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('data_source_template_zones')) {
                                $query->whereIn('template_zone_id', $request->data_source_template_zones);
                            } else {
                                $query->whereNull('template_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('data_source_template_zones') && $assetHasZones) {
                            $fail('The combination of DataSource and Asset Zone already exists.');
                        } else {
                            $fail('The combination of DataSource and Asset already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'data_source_template_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'template_zones.*' => 'nullable|exists:template_zones,template_zone_id',
            'script' => 'required'
        ]);

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source_type = $data_source->data_source_type_id;
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;
        $data['data_source_type_id'] = $data_source_type;

        $createdDataSources = [];

        if (!empty($data['data_source_template_zones'])) {
            foreach ($data['data_source_template_zones'] as $zoneId) {
                if (is_null($zoneId) || $zoneId == 0) {
                    continue;
                }

                $data_source_data = $data;
                $data_source_data['template_zone_id'] = $zoneId;

                $assetDataSource = AssetTemplateDataSource::create($data_source_data);
                $createdDataSources[] = new AssetTemplateDataSourceResource($assetDataSource);

                foreach($request->asset_datasource_attributes as $attribute)
                {
                    TemplateDataSourceValue::create([
                        'asset_template_datasource_id' => $assetDataSource->asset_template_datasource_id,
                        'asset_template_id' => $assetDataSource->asset_template_id,
                        'data_source_id' => $data_source->data_source_id,
                        'template_zone_id' => $assetDataSource->template_zone_id,
                        'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                        'field_value' => $attribute['field_value'] ?? ''
                    ]);
                }
            }
        } else {
            $data_source_data = $data;
            $data_source_data['template_zone_id'] = null;

            $assetDataSource = AssetTemplateDataSource::create($data_source_data);
            $createdDataSources[] = new AssetTemplateDataSourceResource($assetDataSource);

            foreach($request->asset_datasource_attributes as $attribute)
            {
                TemplateDataSourceValue::create([
                    'asset_template_datasource_id' => $assetDataSource->asset_template_datasource_id,
                    'asset_template_id' => $assetDataSource->asset_template_id,
                    'data_source_id' => $data_source->data_source_id,
                    'template_zone_id' => $assetDataSource->template_zone_id,
                    'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                    'field_value' => $attribute['field_value'] ?? ''
                ]);
            }
        }
        return response()->json([$createdDataSources,  "message" => "Template DataSource Created Successfully"]);
    }

    public function getAssetTemplateDataSource(Request $request)
    {
        $request->validate([
            'asset_template_datasource_id' => 'required|exists:asset_template_data_sources,asset_template_datasource_id'
        ]);

        $asset_data_source = AssetTemplateDataSource::where('asset_template_datasource_id',$request->asset_template_datasource_id)->first();
        return new AssetTemplateDataSourceResource($asset_data_source);
    }

    public function updateAssetTemplateDataSource(Request $request)
    {
        $asset_data_sources = AssetTemplateDataSource::where('asset_template_datasource_id', $request->asset_template_datasource_id)->first();
        $assetHasZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();
        $data = $request->validate([
            'asset_template_datasource_id' => 'required|exists:asset_template_datasources,asset_template_datasource_id',
            'data_source_id' => [
                'required',
                'exists:data_sources,data_source_id',
                function ($attribute, $value, $fail) use ($request, $asset_data_sources) 
                {
                    if ($value != $asset_data_sources->data_source_id) {
                        $exists = AssetTemplateDataSource::where('data_source_id', $value)
                            ->where('asset_template_id', $request->asset_template_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('template_zone_id')) {
                                    $query->where('template_zone_id', $request->template_zone_id);
                                } else {
                                    $query->whereNull('template_zone_id');
                                }
                            })->where('asset_template_datasource_id', '!=', $request->asset_template_datasource_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of DataSource, Asset, and Template Zone already exists.');
                        }
                    }
                },
            ],
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'template_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
            'script' => 'required'
        ]);

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source_type = $data_source->data_source_type_id;
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        $data['data_source_type_id'] = $data_source_type;

        $asset_data_source = AssetTemplateDataSource::where('asset_template_datasource_id', $request->asset_template_datasource_id)->first();
        $asset_data_source->update($data);

        if(isset($request->deleted_asset_datasource_values)>0)
        {
            TemplateDataSourceValue::whereIn('template_datasource_value_id', $request->deleted_asset_datasource_values)->forceDelete();
        }

        foreach ($request->asset_datasource_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? '';

            if ($fieldValue !== null) 
            {
                TemplateDataSourceValue::updateOrCreate(
                    [
                        'asset_template_datasource_id' => $asset_data_source->asset_template_datasource_id,
                        'template_zone_id' => $asset_data_source->template_zone_id,
                        'data_source_id' => $data_source->data_source_id,
                        'asset_template_id' =>  $asset_data_source->asset_template_id,
                        'data_source_attribute_id' => $attribute['data_source_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
        return response()->json([
            "message" => "Template DataSource Updated Successfully",
            new AssetTemplateDataSourceResource($asset_data_source)
        ]); 
    }

    public function deleteAssetTemplateDataSource(Request $request)
    {
        $request->validate([
            'asset_template_datasource_id' => 'required|exists:asset_template_datasources,asset_template_datasource_id'
        ]);

        TemplateDataSourceValue::where('asset_template_datasource_id', $request->asset_template_datasource_id)->forceDelete();
        AssetTemplateDataSource::where('asset_template_datasource_id', $request->asset_template_datasource_id)->forceDelete();

        return response()->json([
            'message' => "Template DataSource Deleted Successfully"
        ]);
    }
}
