<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariableType;
use App\Http\Resources\VariableTypeResource;

class VariableTypeController extends Controller
{
    public function paginateVariableTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = VariableType::query();

        if(isset($request->variable_type_code))
        {
            $query->where('variable_type_code',$request->variable_type_code);
        }
        if(isset($request->variable_type_name))
        {
            $query->where('variable_type_name',$request->variable_type_name);
        }  

        if($request->search!='')
        {
            $query->where('variable_type_code', 'like', "%$request->search%")
                ->orWhere('variable_type_name', 'like', "$request->search%");
        }
        $variable_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return VariableTypeResource::collection($variable_type);
    }

    public function getVariableTypes()
    {
        $variable_type = VariableType::all();
        return VariableTypeResource::collection($variable_type);
    }

    public function addVariableType(Request $request)
    {
        $data = $request->validate([
            'variable_type_code' => 'required|string|unique:variable_types,variable_type_code',
            'variable_type_name' => 'required|string|unique:variable_types,variable_type_name'
        ]);
        
        $variable_type = VariableType::create($data);
        return response()->json(["message" => "VariableType Created Successfully"]); 
    }  

    public function getVariableType(Request $request)
    {
        $request->validate([
            'variable_type_id' => 'required|exists:variable_types,variable_type_id'
        ]);

        $variable_type = VariableType::where('variable_type_id',$request->variable_type_id)->first();
        return new VariableTypeResource($variable_type);
    }

    public function updateVariableType(Request $request)
    {
        $data = $request->validate([
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
            'variable_type_code' => 'required|string|unique:variable_types,variable_type_code,'.$request->variable_type_id.',variable_type_id',
            'variable_type_name' => 'required|string|unique:variable_types,variable_type_code,'.$request->variable_type_id.',variable_type_id'
        ]);

        $variable_type = VariableType::where('variable_type_id', $request->variable_type_id)->first();
        $variable_type->update($data);
        return response()->json(["message" => "VariableType Updated Successfully"]); 
    }

    public function deleteVariableType(Request $request)
    {
        $request->validate([
            'variable_type_id' => 'required|exists:variable_types,variable_type_id'
        ]);
        $variable_type = VariableType::withTrashed()->where('variable_type_id',$request->variable_type_id)->first();

        if($variable_type->trashed())
        {
            $variable_type->restore();
            return response()->json([
                "message" => "VariableType Activated successfully"
            ],200);
        }
        else
        {
            $variable_type->delete();
            return response()->json([
                "message" => "VariableType Deactivated successfully"
            ], 200);
        }
    }
}
