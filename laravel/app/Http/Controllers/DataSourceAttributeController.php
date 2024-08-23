<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSourceAttribute;
use App\Models\DataSourceAttributeType;
use App\Http\Resources\DataSourceAttributeResource;
use Illuminate\Support\Facades\Auth;

class DataSourceAttributeController extends Controller
{
    public function paginateDataSourceAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = DataSourceAttribute::query();

        if(isset($request->field_name))
        {
            $query->where('field_name',$request->field_name);
        }
        if(isset($request->display_name))
        {
            $query->where('display_name',$request->display_name);
        }
        if(isset($request->field_values))
        {
            $query->where('field_values',$request->field_values);
        }

        if(isset($request->data_source_type_id))
        {
            $query->where('data_source_type_id',$request->data_source_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "$request->search%")
            ->orwhere('display_name', 'like', "$request->search%")->orwhere('field_values', 'like', "$request->search%")
            ->orwhere('field_type', 'like', "$request->search%")->orwhere('field_length', 'like', "$request->search%")
            ->orwhereHas('DataSourceAttributeTypes', function($que) use($request){
                $que->whereHas('DataSourceType', function($qu) use($request){
                    $qu->where('data_source_type_name', 'like', "$request->search%");
                });
            });    
        }
        $data_source = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return DataSourceAttributeResource::collection($data_source);
    }

    public function getDataSourceAttributes()
    {
        $data_source_attribute = DataSourceAttribute::all();
        return DataSourceAttributeResource::collection($data_source_attribute);
    }

    public function addDataSourceAttribute(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required',
	        'display_name' => 'required',
	        'field_type' => 'required', 
	        'field_values' => 'nullable',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'data_source_types' => 'required|array',
	        'data_source_type_id.*' => 'required|exists:data_source_types,data_source_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $data_source_attribute = DataSourceAttribute::create($data);

        foreach ($data['data_source_types'] as $data_source_type_id) {
            DataSourceAttributeType::create([
                'data_source_attribute_id' => $data_source_attribute->data_source_attribute_id,
                'data_source_type_id' => $data_source_type_id
            ]);
        }
        return new DataSourceAttributeResource($data_source_attribute);  
    }  

    public function getDataSourceAttribute(Request $request)
    {
        $request->validate([
            'data_source_attribute_id' => 'required|exists:data_source_attributes,data_source_attribute_id'
        ]);

        $data_source_attribute = DataSourceAttribute::where('data_source_attribute_id', $request->data_source_attribute_id)->first();
        return new DataSourceAttributeResource($data_source_attribute);
    }

    public function updateDataSourceAttribute(Request $request)
    {
        $data = $request->validate([
            'data_source_attribute_id' => 'required|exists:data_source_attributes,data_source_attribute_id',
            'field_name' => 'required',
            'display_name' => 'required',
            'field_type' => 'required',
            'field_values' => 'nullable',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'data_source_types' => 'required|array',
            'data_source_types.*' => 'required|exists:data_source_types,data_source_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $data_source_attribute = DataSourceAttribute::where('data_source_attribute_id', $request->data_source_attribute_id)->first();
        $data_source_attribute->update($data);

        DataSourceAttributeType::where('data_source_attribute_id', $data_source_attribute->data_source_attribute_id)->delete();

        foreach ($data['data_source_types'] as $data_source_type_id) {
            DataSourceAttributeType::create([
                'data_source_attribute_id' => $data_source_attribute->data_source_attribute_id,
                'data_source_type_id' => $data_source_type_id
            ]);
        }
        return new DataSourceAttributeResource($data_source_attribute);
    }

    public function deleteDataSourceAttribute(Request $request)
    {
        $request->validate([
            'data_source_attribute_id' => 'required|exists:data_source_attributes,data_source_attribute_id'
        ]);

        $data_source_attribute = DataSourceAttribute::withTrashed()->where('data_source_attribute_id', $request->data_source_attribute_id)->first();
       
        if($data_source_attribute->trashed())
        {
            $data_source_attribute->restore();
            return response()->json([
                "message" => "DataSourceAttribute Activated successfully"
            ],200);
        }
        else
        {
            $data_source_attribute->delete();
            return response()->json([
                "message" => "DataSourceAttribute Deactivated successfully"
            ], 200); 
        }
    }
}
