<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BreakDownAttributeValueController extends Controller
{
    public function paginateBreakDownAttributeValues(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = BreakDownAttributeValue::query();

        if(isset($request->break_down_list_code))
        {
            $query->where('break_down_list_code',$request->break_down_list_code);
        }
        if(isset($request->break_down_list_name))
        {
            $query->where('break_down_list_name',$request->break_down_list_name);
        }
        
        if($request->search!='')
        {
            $query->where('break_down_list_code', 'like', "%$request->search%")
                ->orWhere('break_down_list_name', 'like', "$request->search%");
        }
        $break_down_list = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return BreakDownAttributeValueResource::collection($break_down_list);
    }

    public function getBreakDowns()
    {
        $userPlantId = Auth::User()->plant_id;
    
        $break_down = BreakDown::where('plant_id', $userPlantId)->get();
        return BreakDownResource::collection($break_down);
    }

    public function addBreakDownAttributeValue(Request $request)
    {
        $data = $request->validate([
            'break_down_list_code' => 'required',
            'break_down_list_name' => 'required',
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id',
            'break_down_attribute_id' => 'required|exists:break_down_attributes,break_down_attribute_id',
            'field_value' => 'required'
        ]);

        $break_down = BreakDownAttributeValue::create($data);
        return new BreakDownAttributeValueResource($break_down);
    }

    public function getBreakDown(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);

        $break_down = BreakDownList::where('break_down_list_id',$request->break_down_list_id)->first();
        return new BreakDownListResource($break_down);
    }

    public function getBreakDownCode(Request $request)
    {
        $request->validate([
            'break_down_list_code' => 'required'
        ]);

        $break_down = BreakDownList::where('break_down_list_code',$request->break_down_list_code)->first();
        return new BreakDownListResource($break_down);
    }

    public function updateBreakDown(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'break_down_list_code' => 'required|string|unique:break_down_lists,break_down_list_code,'.$request->break_down_list_id.',break_down_list_id',
            'break_down_list_name' => 'required|string|unique:break_down_lists,break_down_list_name,'.$request->break_down_list_id.',break_down_list_id',
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id',
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

        $break_down = BreakDownList::where('break_down_list_id', $request->break_down_list_id)->first();
        $break_down->update($data);
        return response()->json(["message" => "BreakDownList Updated Successfully"]);
    }

    public function deleteBreakDown(Request $request)
    {
        $request->validate([
            'break_down_list_id' => 'required|exists:break_down_lists,break_down_list_id'
        ]);

        $break_down = BreakDownList::withTrashed()->where('break_down_list_id', $request->break_down_list_id)->first();

        if($break_down->trashed())
        {
            $break_down->restore();
            return response()->json([
                "message" =>"BreakDownList Activated successfully"
            ],200);
        }
        else
        {
            $break_down->delete();
            return response()->json([
                "message" =>"BreakDownList Deactivated successfully"
            ], 200); 
        }
    }

    public function getBreakDownsDropdown(Request $request)
    {
        $request->validate([
            'break_down_type_id' => 'required|exists:break_down_types,break_down_type_id'
        ]);

        $break_down_type = BreakDownAttribute::whereHas('BreakDownAttributeTypes', function($que) use($request){
            $que->where('break_down_type_id', $request->break_down_type_id);
        })->get();

        return BreakDownAttributeResource::collection($break_down_type);
    }
}
