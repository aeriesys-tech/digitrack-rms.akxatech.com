<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSourceType;
use App\Http\Resources\DataSourceTypeResource;

class DataSourceTypeController extends Controller
{
    public function paginateDataSourceTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = DataSourceType::query();

        if(isset($request->data_source_type_code))
        {
            $query->where('data_source_type_code',$request->data_source_type_code);
        }
        if(isset($request->data_source_type_name))
        {
            $query->where('data_source_type_name',$request->data_source_type_name);
        }  

        if($request->search!='')
        {
            $query->where('data_source_type_code', 'like', "%$request->search%")
                ->orWhere('data_source_type_name', 'like', "%$request->search%");
        }
        
        $data_source_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return DataSourceTypeResource::collection($data_source_type);
    }

    public function getDataSourceTypes()
    {
        $data_source_type = DataSourceType::all();
        return DataSourceTypeResource::collection($data_source_type);
    }

    public function addDataSourceType(Request $request)
    {
        $data = $request->validate([
            'data_source_type_code' => 'required|string|unique:data_source_types,data_source_type_code',
            'data_source_type_name' => 'required|string|unique:data_source_types,data_source_type_name'
        ]);
        
        $data_source_type = DataSourceType::create($data);
        return response()->json(["message" => "DataSource Type Created Successfully"]); 
    } 
    
    public function getDataSourceType(Request $request)
    {
        $request->validate([
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id'
        ]);

        $data_source_type = DataSourceType::where('data_source_type_id',$request->data_source_type_id)->first();
        return new DataSourceTypeResource($data_source_type);
    }

    public function updateDataSourceType(Request $request)
    {
        $data = $request->validate([
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id',
            'data_source_type_code' => 'required|unique:data_source_types,data_source_type_code,'.$request->data_source_type_id.',data_source_type_id',
            'data_source_type_name' => 'required|unique:data_source_types,data_source_type_name,'.$request->data_source_type_id.',data_source_type_id'
        ]);

        $data_source_type = DataSourceType::where('data_source_type_id', $request->data_source_type_id)->first();
        $data_source_type->update($data);
        return response()->json(["message" => "DataSource Type Updated Successfully"]);  
    }

    public function deleteDataSourceType(Request $request)
    {
        $request->validate([
            'data_source_type_id' => 'required|exists:data_source_types,data_source_type_id'
        ]);

        $data_source_type = DataSourceType::withTrashed()->where('data_source_type_id',$request->data_source_type_id)->first();

        if($data_source_type->trashed())
        {
            $data_source_type->restore();
            return response()->json([
                "message" =>"DataSource Type Activated Successfully"
            ],200);
        }
        else
        {
            $data_source_type->delete();
            return response()->json([
                "message" =>"DataSource Type Deactivated Successfully"
            ], 200); 
        }
    }
}
