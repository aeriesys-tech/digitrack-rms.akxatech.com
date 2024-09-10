<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variable;
use App\Models\VariableAssetType;
use App\Http\Resources\VariableResource;
use Auth;
use App\Models\VariableAttributeValue;
use App\Models\VariableAttribute;
use App\Http\Resources\VariableAttributeResource;

class VariableController extends Controller
{
    public function paginateVariables(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Variable::query();

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
        return VariableResource::collection($variable);
    }

    public function getVariables()
    {
        $variable = Variable::all();
        return VariableResource::collection($variable);
    }

    public function addVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'variable_code' => 'required|string|unique:variables,variable_code',
            'variable_name' => 'required|string|unique:variables,variable_name',
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
            'variable_attributes' => 'required|array',
            'variable_attributes.*.variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id',
            'variable_attributes.*.variable_attribute_value.field_value' => 'required|string',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id'
        ]);
        $data['plant_id'] = $userPlantId;

        
        $variable = Variable::create($data);

        foreach ($data['asset_types'] as $asset_type) {
            VariableAssetType::create([
                'variable_id' => $variable->variable_id,
                'asset_type_id' => $asset_type,
            ]);
        }

        foreach ($request->variable_attributes as $attribute) {
            VariableAttributeValue::create([
                'variable_id' => $variable->variable_id,
                'variable_attribute_id' => $attribute['variable_attribute_id'],
                'field_value' => $attribute['variable_attribute_value']['field_value'] ?? '',
            ]);
        }
        
        return response()->json(["message" => "Variable Created Successfully"]);
    }

    public function getVariable(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);

        $variable = Variable::where('variable_id',$request->variable_id)->first();
        return new VariableResource($variable);
    }

    public function getVariableData(Request $request)
    {
        $request->validate([
            'variable_id' => 'required|exists:variables,variable_id'
        ]);

        $variable = Variable::where('variable_id',$request->variable_id)->first();
        return new VariableResource($variable);
    }

    public function getAssetTypeVariables(Request $request)
    {
        $request->validate([
            'asset_type_id' => 'required|exists:asset_type,asset_type_id'
        ]);

        $variables = Variable::whereHas('VariableAssetTypes', function($que) use($request){
            $que->where('asset_type_id', $request->asset_type_id);
        })->get();
        return VariableResource::collection($variables);
    }

    public function updateVariable(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'variable_id' => 'required|exists:variables,variable_id',
            'variable_code' => 'required|string|unique:variables,variable_code,' . $request->variable_id . ',variable_id',
            'variable_name' => 'required|string|unique:variables,variable_name,' . $request->variable_id . ',variable_id',
            'variable_type_id' => 'required|exists:variable_types,variable_type_id',
            'variable_attributes' => 'required|array',
            'variable_attributes.*.variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id',
            'variable_attributes.*.variable_attribute_value.field_value' => 'required|string',
            'asset_types' => 'required|array',
	        'asset_type_id.*' => 'required|exists:asset_types,asset_type_id',
            'deleted_variable_attribute_values' => 'nullable'
        ]);
    
        $data['plant_id'] = $userPlantId;
    
        $variable = Variable::where('variable_id', $request->variable_id)->first();
        $variable->update($data);

        VariableAssetType::where('variable_id', $variable->variable_id)->delete();

        foreach ($data['asset_types'] as $asset_type) {
            VariableAssetType::create([
                'variable_id' => $variable->variable_id,
                'asset_type_id' => $asset_type,
            ]);
        }

        if($request->deleted_variable_attribute_values > 0)
        {
            VariableAttributeValue::whereIn('variable_attribute_value_id', $request->deleted_variable_attribute_values)->forceDelete();
        }
    
        foreach ($request->variable_attributes as $attribute) 
        {
            $fieldValue = $attribute['field_value'] ?? $attribute['variable_attribute_value']['field_value'] ?? null;
    
            if ($fieldValue !== null) {
                VariableAttributeValue::updateOrCreate(
                    [
                        'variable_id' => $variable->variable_id,
                        'variable_attribute_id' => $attribute['variable_attribute_id'],
                    ],
                    [
                        'field_value' => $fieldValue,
                    ]
                );
            }
        }
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
