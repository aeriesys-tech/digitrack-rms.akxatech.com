<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariableAttribute;
use App\Models\VariableAttributeType;
use App\Http\Resources\VariableAttributeResource;
use Illuminate\Support\Facades\Auth;

class VariableAttributeController extends Controller
{
    public function paginateVariableAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = VariableAttribute::query();

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

        if(isset($request->variable_type_id))
        {
            $query->where('variable_type_id',$request->variable_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "$request->search%")
            ->orwhere('display_name', 'like', "$request->search%")->orwhere('field_values', 'like', "$request->search%")
            ->orwhere('field_type', 'like', "$request->search%")->orwhere('field_length', 'like', "$request->search%")
            ->orwhereHas('VariableAttributeTypes', function($que) use($request){
                $que->whereHas('VariableType', function($qu) use($request){
                    $qu->where('variable_type_name', 'like', "$request->search%");
                });
            });    
        }
        $variable_attribute = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return VariableAttributeResource::collection($variable_attribute);
    }

    public function getVariableAttributes()
    {
        $variable_attribute = VariableAttribute::all();
        return VariableAttributeResource::collection($variable_attribute);
    }

    public function addVariableAttribute(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required|string|unique:variable_attributes,field_name',
	        'display_name' => 'required|string|unique:variable_attributes,display_name',
	        'field_type' => 'required', 
	        'field_values' => 'nullable|required_if:field_type,Dropdown',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'variable_types' => 'required|array',
	        'variable_type_id.*' => 'required|exists:variable_types,variable_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $variable_attribute = VariableAttribute::create($data);

        foreach ($data['variable_types'] as $variable_type_id) {
            VariableAttributeType::create([
                'variable_attribute_id' => $variable_attribute->variable_attribute_id,
                'variable_type_id' => $variable_type_id
            ]);
        }
        return new VariableAttributeResource($variable_attribute);  
    } 

    public function getVariableAttribute(Request $request)
    {
        $request->validate([
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id'
        ]);

        $variable_attribute = VariableAttribute::where('variable_attribute_id', $request->variable_attribute_id)->first();
        return new VariableAttributeResource($variable_attribute);
    }

    public function updateVariableAttribute(Request $request)
    {
        $data = $request->validate([
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id',
            'field_name' => 'required|string|unique:variable_attributes,field_name,'.$request->variable_attribute_id .',variable_attribute_id',
	        'display_name' => 'required|string|unique:variable_attributes,display_name,'.$request->variable_attribute_id .',variable_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'variable_types' => 'required|array',
            'variable_types.*' => 'required|exists:variable_types,variable_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $variable_attribute = VariableAttribute::where('variable_attribute_id', $request->variable_attribute_id)->first();
        $variable_attribute->update($data);

        VariableAttributeType::where('variable_attribute_id', $variable_attribute->variable_attribute_id)->delete();

        foreach ($data['variable_types'] as $variable_type_id) {
            VariableAttributeType::create([
                'variable_attribute_id' => $variable_attribute->variable_attribute_id,
                'variable_type_id' => $variable_type_id
            ]);
        }
        return new VariableAttributeResource($variable_attribute);
    }

    public function deleteVariableAttribute(Request $request)
    {
        $request->validate([
            'variable_attribute_id' => 'required|exists:variable_attributes,variable_attribute_id'
        ]);

        $variable_attribute = VariableAttribute::withTrashed()->where('variable_attribute_id', $request->variable_attribute_id)->first();
       
        if($variable_attribute->trashed())
        {
            $variable_attribute->restore();
            return response()->json([
                "message" => "VariableAttribute Activated successfully"
            ],200);
        }
        else
        {
            $variable_attribute->delete();
            return response()->json([
                "message" => "VariableAttribute Deactivated successfully"
            ], 200); 
        }
    }
}
