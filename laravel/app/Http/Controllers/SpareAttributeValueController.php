<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpareAttributeValue;
use App\Http\Resources\SpareAttributeValueResource;

class SpareAttributeValueController extends Controller
{
    public function paginateSpareAttributeValues(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = SpareAttributeValue::query();

        if(isset($request->spare_code))
        {
            $query->where('spare_code',$request->spare_code);
        }
        if(isset($request->spare_name))
        {
            $query->where('spare_name',$request->spare_name);
        }
        
        if($request->search!='')
        {
            $query->where('spare_code', 'like', "%$request->search%")
                ->orWhere('spare_name', 'like', "$request->search%");
        }
        $spare = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return SpareAttributeValueResource::collection($spare);
    }

    public function getSpares()
    {
        $userPlantId = Auth::User()->plant_id;
    
        $spare = Spare::where('plant_id', $userPlantId)->get();
        return SpareResource::collection($spare);
    }

    public function addSpareAttributeValue(Request $request)
    {
        $data = $request->validate([
            'spare_code' => 'required',
            'spare_name' => 'required',
            'spare_type_id' => 'required|exists:spare_type,spare_type_id',
            'spare_attribute_id' => 'required|exists:spare_attributes,spare_attribute_id',
            'field_value' => 'required'
        ]);

        $spare = SpareAttributeValue::create($data);
        return new SpareAttributeValueResource($spare);
    }

    public function getSpare(Request $request)
    {
        $request->validate([
            'spare_id' => 'required|exists:spares,spare_id'
        ]);

        $spare = Spare::where('spare_id',$request->spare_id)->first();
        return new SpareResource($spare);
    }

    public function getSpareCode(Request $request)
    {
        $request->validate([
            'spare_code' => 'required'
        ]);

        $spare = Spare::where('spare_code',$request->spare_code)->first();
        return new SpareResource($spare);
    }

    public function updateSpare(Request $request)
    {
        $userPlantId = Auth::User()->plant_id;
        $data = $request->validate([
            'spare_code' => 'required|string|unique:spares,spare_code,'.$request->spare_id.',spare_id',
            'spare_name' => 'required|string|unique:spares,spare_name,'.$request->spare_id.',spare_id',
            'spare_type_id' => 'required|exists:spare_type,spare_type_id',
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

        $spare = Spare::where('spare_id', $request->spare_id)->first();
        $spare->update($data);
        return response()->json(["message" => "Spare Updated Successfully"]);
    }

    public function deleteSpare(Request $request)
    {
        $request->validate([
            'spare_id' => 'required|exists:spares,spare_id'
        ]);
        $spare = Spare::withTrashed()->where('spare_id', $request->spare_id)->first();

        if($spare->trashed())
        {
            $spare->restore();
            return response()->json([
                "message" =>"Spare Activated successfully"
            ],200);
        }
        else
        {
            $spare->delete();
            return response()->json([
                "message" =>"Spare Deactivated successfully"
            ], 200); 
        }
    }

    public function getSparesDropdown(Request $request)
    {
        $request->validate([
            'spare_type_id' => 'required|exists:spare_type,spare_type_id'
        ]);

        $spare_type = SpareAttribute::whereHas('SpareAttributeTypes', function($que) use($request){
            $que->where('spare_type_id', $request->spare_type_id);
        })->get();

        return SpareAttributeResource::collection($spare_type);
    }
}
