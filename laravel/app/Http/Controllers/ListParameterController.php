<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListParameter;
use App\Http\Resources\ListParameterResource;

class ListParameterController extends Controller
{
    public function paginateListParameters(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = ListParameter::query();

        if(isset($request->list_parameter_name))
        {
            $query->where('list_parameter_name',$request->list_parameter_name);
        }

        if(isset($request->field_values))
        {
            $query->where('field_values',$request->field_values);
        }
        
        if($request->search!='')
        {
            $query->where('list_parameter_name', 'like', "%$request->search%")
            ->orWhere('field_values', 'like', "%$request->search%");
        }
        $list_parameter = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ListParameterResource::collection($list_parameter);
    }

    public function getListParameters()
    {
        $list_parameter = ListParameter::all();
        return ListParameterResource::collection($list_parameter);
    }

    public function addListParameter(Request $request)
    {
        $data = $request->validate([
            'list_parameter_name' => 'required',
            'field_values' => 'required'
        ]);

        $list_parameter = ListParameter::create($data);
        return response()->json(["message" => "ListParameter Created Successfully"]); 
    }

    public function getListParameter(Request $request)
    {
        $request->validate([
            'list_parameter_id' => 'required|exists:list_parameters,list_parameter_id'
        ]);

        $list_parameter = ListParameter::where('list_parameter_id',$request->list_parameter_id)->first();
        return new ListParameterResource($list_parameter);
    } 

    public function updateListParameter(Request $request)
    {
        $data = $request->validate([
            'list_parameter_id' => 'required|exists:list_parameters,list_parameter_id',
            'list_parameter_name' => 'required',
            'field_values' => 'required'
        ]);

        $list_parameter = ListParameter::where('list_parameter_id',$request->list_parameter_id)->first();
        $list_parameter->update($data);
        return response()->json(["message" => "ListParameter Updated Successfully"]);
    }

    public function deleteListParameter(Request $request)
    {
        $request->validate([
            'list_parameter_id' => 'required|exists:list_parameters,list_parameter_id'
        ]);

        $list_parameter = ListParameter::withTrashed()->where('list_parameter_id',$request->list_parameter_id)->first();
        if($list_parameter->trashed()) 
        {
            $list_parameter->restore();
            return response()->json([
                "message" =>"ListParameter Activated successfully"
            ],200);
        } 
        else 
        {
            $list_parameter->delete();
            return response()->json([
                "message" =>"ListParameter Deactivated successfully"
            ], 200); 
        }
    }
}
