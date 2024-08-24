<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariableAttributeValue;
use App\Http\Resources\VariableAttributeValueResource;

class VariableAttributeValueController extends Controller
{
    public function paginateVariableAttributeValues(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = VariableAttributeValue::query();

        if(isset($request->variable_code))
        {
            $query->where('variable_code',$request->variable_code);
        }
        if(isset($request->variable_name))
        {
            $query->where('variable_name',$request->variable_name);
        }
        
        if($request->search!='')
        {
            $query->where('variable_code', 'like', "%$request->search%")
                ->orWhere('variable_name', 'like', "$request->search%");
        }
        $variable = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return VariableAttributeValueResource::collection($variable);
    }

    public function getVariables()
    {
        $userPlantId = Auth::User()->plant_id;
    
        $variable = Variable::where('plant_id', $userPlantId)->get();
        return VariableResource::collection($variable);
    }

    public function addVariableAttributeValue(Request $request)
    {
        $data = $request->validate([
            'variable_code' => 'required',
            'variable_name' => 'required',
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id',
            'field_value' => 'required'
        ]);

        $variable = VariableAttributeValue::create($data);
        return new VariableAttributeValueResource($variable);
    }

    public function getVariable(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);

        $variable = Variable::where('variable_id',$request->variable_id)->first();
        return new VariableResource($variable);
    }

    public function getVariableCode(Request $request)
    {
        $request->validate([
            'variable_code' => 'required'
        ]);

        $variable = Variable::where('variable_code',$request->variable_code)->first();
        return new VariableResource($variable);
    }

    public function updateVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'variable_code' => 'required|string|unique:variables,variable_code,'.$request->variable_id.',variable_id',
            'variable_name' => 'required|string|unique:variables,variable_name,'.$request->variable_id.',variable_id',
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
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

        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable->update($data);
        return response()->json(["message" => "Variable Updated Successfully"]);
    }

    public function deleteVariable(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);
        $variable = Variable::withTrashed()->where('variable_id', $request->variable_id)->first();

        if($variable->trashed())
        {
            $variable->restore();
            return response()->json([
                "message" => "Variable Activated successfully"
            ],200);
        }
        else
        {
            $variable->delete();
            return response()->json([
                "message" => "Variable Deactivated successfully"
            ], 200); 
        }
    }

    public function getVariablesDropdown(Request $request)
    {
        $request->validate([
            'variable_type_id' => 'required|exists:variable_types,variable_type_id'
        ]);

        $variable_type = VariableAttribute::whereHas('VariableAttributeTypes', function($que) use($request){
            $que->where('variable_type_id', $request->variable_type_id);
        })->get();

        return VariableAttributeResource::collection($variable_type);
    }
}
