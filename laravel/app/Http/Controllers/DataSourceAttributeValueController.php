<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSourceAttributeValue;
use App\Http\Resources\DataSourceAttributeValueResource;

class DataSourceAttributeValueController extends Controller
{
    public function paginateDataSourceAttributeValues(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = DataSourceAttributeValue::query();

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
        return DataSourceAttributeValueResource::collection($data_source);
    }

    public function getDataSources()
    {
        $userPlantId = Auth::User()->plant_id;
    
        $data_source = DataSource::where('plant_id', $userPlantId)->get();
        return DataSourceResource::collection($data_source);
    }

    public function addDataSourceAttributeValue(Request $request)
    {
        $data = $request->validate([
            'data_source_code' => 'required',
            'data_source_name' => 'required',
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id',
            'data_source_attribute_id' => 'required|exists:data_source_attributes,data_source_attribute_id',
            'field_value' => 'required'
        ]);

        $data_source = DataSourceAttributeValue::create($data);
        return new DataSourceAttributeValueResource($data_source);
    }

    public function getDataSource(Request $request)
    {
        $request->validate([
            'data_source_id' => 'required|exists:data_sources,data_source_id'
        ]);

        $data_source = DataSource::where('data_source_id',$request->data_source_id)->first();
        return new DataSourceResource($data_source);
    }

    public function getDataSourceCode(Request $request)
    {
        $request->validate([
            'data_source_code' => 'required'
        ]);

        $data_source = DataSource::where('data_source_code',$request->data_source_code)->first();
        return new DataSourceResource($data_source);
    }

    public function updateDataSource(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'data_source_code' => 'required|string|unique:data_sources,data_source_code,'.$request->data_source_id.',data_source_id',
            'data_source_name' => 'required|string|unique:data_sources,data_source_name,'.$request->data_source_id.',data_source_id',
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id',
            'voltage_id' => 'required|exists:voltages,voltage_id',
            'watt_rating_id' => 'required|exists:watt_ratings,watt_rating_id',
            'frame_id' => 'required|exists:frames,frame_id',
            'mounting_id' => 'required|exists:mountings,mounting_id',
            'section_id' => 'required|exists:sections,section_id',
            'make_id' => 'required|exists:makes,make_id',
            'speed_id' => 'required|exists:speeds,speed_id',
            'serial_no' => 'nullable|sometimes'
        ]);
        $data['plant_id'] = $userPlantId;

        $data_source = DataSource::where('data_source_id', $request->data_source_id)->first();
        $data_source->update($data);
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
