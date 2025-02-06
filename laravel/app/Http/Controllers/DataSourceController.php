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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataSourceExport;
use App\Exports\DataSourceHeadingsExport;
use App\Imports\DataSourceImport;

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
                ->orWhere('data_source_name', 'like', "%$request->search%")
                ->orwhereHas('DataSourceType', function($que) use($request){
                    $que->where('data_source_type_name', 'like', "%$request->search%");
                })->orwhereHas('DataSourceAssetTypes', function($que) use($request){
                    $que->whereHas('AssetType', function($qu) use($request){
                        $qu->where('asset_type_name', 'like', "%$request->search%");
                    });
                });
        }

        if ($request->keyword == 'data_source_type_name') {
            $query->join('data_source_types', 'data_sources.data_source_type_id', '=', 'data_source_types.data_source_type_id')->select('data_sources.*') 
                  ->orderBy('data_source_types.data_source_type_name', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $data_source = $query->withTrashed()->paginate($request->per_page); 
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
            'data_source_attributes' => 'nullable|array',
            'data_source_attributes.*.data_source_attribute_id' => 'nullable|exists:data_source_attributes,data_source_attribute_id',
            'data_source_attributes.*.data_source_attribute_value.field_value' => 'nullable',
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
            'data_source_attributes' => 'nullable|array',
            'data_source_attributes.*.data_source_attribute_id' => 'nullable|exists:data_source_attributes,data_source_attribute_id',
            'data_source_attributes.*.data_source_attribute_value.field_value' => 'nullable',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'deleted_data_source_attribute_values' => 'nullable'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source->update($data);

        if(isset($request->deleted_data_source_asset_types) > 0)
        {
            DataSourceAssetType::whereIn('data_source_asset_type_id', $request->deleted_data_source_asset_types)->forceDelete();
        }

        foreach ($data['asset_types'] as $asset_type_id) 
        {
            $dataSourceType = DataSourceAssetType::where('data_source_id', $data_source->data_source_id)->where('asset_type_id', $asset_type_id)->first();
            if($dataSourceType)
            {
                $dataSourceType->update([
                    'asset_type_id' => $asset_type_id,
                ]);
            }
            else {
                DataSourceAssetType::create([
                    'data_source_id' => $data_source->data_source_id,
                    'asset_type_id' => $asset_type_id
                ]);
            }
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
                "message" => "DataSource Activated Successfully"
            ],200);
        }
        else
        {
            $data_source->delete();
            return response()->json([
                "message" => "DataSource Deactivated Successfully"
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

    public function downloadDataSources(Request $request)
    {
        $filename = "DataSource.xlsx";

        $excel = new DataSourceExport();

        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function downloadDataSourceHeadings(Request $request)
    {
        $filename = "DataSource Headings.xlsx";
        $excel = new DataSourceHeadingsExport($request->data_source_type_ids);
        
        return Excel::download($excel, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importDataSource(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new DataSourceImport, $request->file('file'));

        return response()->json(['success' => 'Data imported successfully!']);
    }

    public function deleteHardDataSource(Request $request)
    {
        $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id'
        ]);

        DataSourceAssetType::whereIn('data_source_id', $request->data_source_id)->forceDelete();
        DataSourceAttributeValue::whereIn('data_source_id', $request->data_source_id)->forceDelete();
        DataSource::whereIn('data_source_id', $request->data_source_id)->forceDelete();

        return response()->json([
            "message" =>"Data Source Deleted Successfully"
        ],200);
    }
}
