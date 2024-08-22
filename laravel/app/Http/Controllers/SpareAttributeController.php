<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpareAttribute;
use App\Models\SpareAttributeType;
use App\Http\Resources\SpareAttributeResource;
use Illuminate\Support\Facades\Auth;

class SpareAttributeController extends Controller
{
    public function paginateSpareAttributes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = SpareAttribute::query();

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

        if(isset($request->spare_type_id))
        {
            $query->where('spare_type_id',$request->spare_type_id);
        }
        
        if($request->search!='')
        {
            $query->where('field_name', 'like', "$request->search%")
            ->orwhere('display_name', 'like', "$request->search%")->orwhere('field_values', 'like', "$request->search%")
            ->orwhere('field_type', 'like', "$request->search%")->orwhere('field_length', 'like', "$request->search%")
            ->orwhereHas('SpareType', function($que) use($request){
                $que->where('spare_type_name', 'like', "$request->search%");
            });
        }
        $spare_attribute = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return spareAttributeResource::collection($spare_attribute);
    }

    public function getSpareAttributes()
    {
        $spare_attribute = SpareAttribute::all();
        return SpareAttributeResource::collection($spare_attribute);
    }

    public function addSpareAttribute(Request $request)
    {
        $data = $request->validate([
        	'field_name' => 'required',
	        'display_name' => 'required',
	        'field_type' => 'required', 
	        'field_values' => 'nullable',
	        'field_length' => 'required',
	        'is_required' => 'required|boolean',
            'spare_types' => 'required|array',
	        'spare_type_id.*' => 'required|exists:spare_types,spare_type_id'
        ]);
        $data['user_id'] = Auth::id();
        
        $spare_attribute = SpareAttribute::create($data);

        foreach ($data['spare_types'] as $spare_tpe_id) {
            SpareAttributeType::create([
                'spare_attribute_id' => $spare_attribute->spare_attribute_id,
                'spare_type_id' => $spare_tpe_id
            ]);
        }
        return new SpareAttributeResource($spare_attribute);  
    }  

    public function getSpareAttribute(Request $request)
    {
        $request->validate([
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id'
        ]);

        $spare_attribute = SpareAttribute::where('spare_attribute_id', $request->spare_attribute_id)->first();
        return new SpareAttributeResource($spare_attribute);
    }

    public function updateSpareAttribute(Request $request)
    {
        $data = $request->validate([
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id',
            'field_name' => 'required',
            'display_name' => 'required',
            'field_type' => 'required',
            'field_values' => 'nullable',
            'field_length' => 'required',
            'is_required' => 'required|boolean',
            'spare_types' => 'required|array',
            'spare_types.*' => 'required|exists:spare_type,spare_type_id'
        ]);

        $data['user_id'] = Auth::id();

        $spare_attribute = SpareAttribute::where('spare_attribute_id', $request->spare_attribute_id)->first();
        $spare_attribute->update($data);

        SpareAttributeType::where('spare_attribute_id', $spare_attribute->spare_attribute_id)->delete();

        foreach ($data['spare_types'] as $spare_type_id) {
            SpareAttributeType::create([
                'spare_attribute_id' => $spare_attribute->spare_attribute_id,
                'spare_type_id' => $spare_type_id
            ]);
        }
        return new SpareAttributeResource($spare_attribute);
    }

    public function deleteSpareAttribute(Request $request)
    {
        $request->validate([
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id'
        ]);

        $spare_attribute = SpareAttribute::withTrashed()->where('spare_attribute_id', $request->spare_attribute_id)->first();
       
        if($spare_attribute->trashed())
        {
            $spare_attribute->restore();
            return response()->json([
                "message" => "SpareAttribute Activated successfully"
            ],200);
        }
        else
        {
            $spare_attribute->delete();
            return response()->json([
                "message" => "SpareAttribute Deactivated successfully"
            ], 200); 
        }
    }
}
