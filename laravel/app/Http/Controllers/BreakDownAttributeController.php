<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakDownAttribute;
use App\Models\BreakDownAttributeType;
use App\Http\Resources\BreakDownAttributeResource;
use Illuminate\Support\Facades\Auth;

class BreakDownAttributeController extends Controller
{
    public function paginateBreakDownAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = BreakDownAttribute::query();

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

        if(isset($request->break_down_type_id))
        {
            $query->where('break_down_type_id',$request->break_down_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "$request->search%")
            ->orwhere('display_name', 'like', "$request->search%")->orwhere('field_values', 'like', "$request->search%")
            ->orwhere('field_type', 'like', "$request->search%")->orwhere('field_length', 'like', "$request->search%")
            ->orwhereHas('BreakDownAttributeTypes', function($que) use($request){
                $que->whereHas('BreakDownType', function($qu) use($request){
                    $qu->where('break_down_type_name', 'like', "$request->search%");
                });
            });    
        }
        $break_down_attribute = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return BreakDownAttributeResource::collection($break_down_attribute);
    }

    public function getBreakDownAttributes()
    {
        $break_down_attribute = BreakDownAttribute::all();
        return BreakDownAttributeResource::collection($break_down_attribute);
    }

    public function addBreakDownAttribute(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required|string|unique:break_down_attributes,field_name',
	        'display_name' => 'required|string|unique:break_down_attributes,display_name',
	        'field_type' => 'required', 
	        'field_values' => 'nullable|required_if:field_type,Dropdown',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'break_down_types' => 'required|array',
	        'break_down_type_id.*' => 'required|exists:break_down_types,break_down_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $break_down_attribute = BreakDownAttribute::create($data);

        foreach ($data['break_down_types'] as $break_down_type_id) {
            BreakDownAttributeType::create([
                'break_down_attribute_id' => $break_down_attribute->break_down_attribute_id,
                'break_down_type_id' => $break_down_type_id
            ]);
        }
        return new BreakDownAttributeResource($break_down_attribute);  
    } 

    public function getBreakDownAttribute(Request $request)
    {
        $request->validate([
            'break_down_attribute_id' => 'required|exists:break_down_attributes,break_down_attribute_id'
        ]);

        $break_down_attribute = BreakDownAttribute::where('break_down_attribute_id', $request->break_down_attribute_id)->first();
        return new BreakDownAttributeResource($break_down_attribute);
    }

    public function updateBreakDownAttribute(Request $request)
    {
        $data = $request->validate([
            'break_down_attribute_id' => 'required|exists:break_down_attributes,break_down_attribute_id',
            'field_name' => 'required|string|unique:break_down_attributes,field_name,'.$request->break_down_attribute_id .',break_down_attribute_id',
	        'display_name' => 'required|string|unique:break_down_attributes,display_name,'.$request->break_down_attribute_id .',break_down_attribute_id',
            'field_type' => 'required',
            'field_values' => 'nullable|required_if:field_type,Dropdown',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'list_parameter_id' => 'nullable|exists:list_parameters,list_parameter_id|required_if:field_type,List',
            'break_down_types' => 'required|array',
            'break_down_types.*' => 'required|exists:break_down_types,break_down_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $break_down_attribute = BreakDownAttribute::where('break_down_attribute_id', $request->break_down_attribute_id)->first();
        $break_down_attribute->update($data);

        if(isset($request->deleted_break_down_attribute_types) > 0)
        {
            BreakDownAttributeType::whereIn('break_down_attribute_type_id', $request->deleted_break_down_attribute_types)->forceDelete();
        }

        foreach ($data['break_down_types'] as $break_down_type_id) 
        {
            $breakdownType = BreakDownAttributeType::where('break_down_attribute_id', $break_down_attribute->break_down_attribute_id)
            ->where('break_down_type_id', $break_down_type_id)->first();

            if($breakdownType)
            {
                $breakdownType->update([
                    'break_down_type_id' => $break_down_type_id
                ]);
            }
            else{
                BreakDownAttributeType::create([
                    'break_down_attribute_id' => $break_down_attribute->break_down_attribute_id,
                    'break_down_type_id' => $break_down_type_id
                ]);
            }
        }
        return new BreakDownAttributeResource($break_down_attribute);
    }

    public function deleteBreakDownAttribute(Request $request)
    {
        $request->validate([
            'break_down_attribute_id' => 'required|exists:break_down_attributes,break_down_attribute_id'
        ]);

        $break_down_attribute = BreakDownAttribute::withTrashed()->where('break_down_attribute_id', $request->break_down_attribute_id)->first();
       
        if($break_down_attribute->trashed())
        {
            $break_down_attribute->restore();
            return response()->json([
                "message" => "BreakDownAttribute Activated successfully"
            ],200);
        }
        else
        {
            $break_down_attribute->delete();
            return response()->json([
                "message" => "BreakDownAttribute Deactivated successfully"
            ], 200); 
        }
    }
}
