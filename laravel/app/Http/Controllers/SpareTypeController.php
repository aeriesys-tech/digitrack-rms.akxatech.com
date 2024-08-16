<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpareType;
use App\Http\Resources\SpareTypeResource;

class SpareTypeController extends Controller
{
    public function paginateSpareTypes(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = SpareType::query();

        if(isset($request->spare_type_code))
        {
            $query->where('spare_type_code',$request->spare_type_code);
        }
        if(isset($request->spare_type_name))
        {
            $query->where('spare_type_name',$request->spare_type_name);
        }  

        if($request->search!='')
        {
            $query->where('spare_type_code', 'like', "%$request->search%")
                ->orWhere('spare_type_name', 'like', "$request->search%");
        }
        $spare_type = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return SpareTypeResource::collection($spare_type);
    }

    public function getSpareTypes()
    {
        $spare_type = SpareType::all();
        return SpareTypeResource::collection($spare_type);
    }

    public function addSpareType(Request $request)
    {
        $data = $request->validate([
            'spare_type_code' => 'required|string|unique:spare_types,spare_type_code',
            'spare_type_name' => 'required|string|unique:spare_types,spare_type_name'
        ]);
        
        $spare_type = SpareType::create($data);
        return response()->json(["message" => "SpareType Created Successfully"]); 
    }  

    public function getSpareType(Request $request)
    {
        $request->validate([
            'spare_type_id' => 'required|exists:spare_types,spare_type_id'
        ]);

        $spare_type = SpareType::where('spare_type_id',$request->spare_type_id)->first();
        return new SpareTypeResource($spare_type);
    }

    public function updateSpareType(Request $request)
    {
        $data = $request->validate([
            'spare_type_id' => 'required|exists:spare_types,spare_type_id',
            'spare_type_code' => 'required|string|unique:spare_types,spare_type_code,'.$request->spare_type_id.',spare_type_id',
            'spare_type_name' => 'required|string|unique:spare_types,spare_type_code,'.$request->spare_type_id.',spare_type_id'
        ]);

        $spare_type = SpareType::where('spare_type_id', $request->spare_type_id)->first();
        $spare_type->update($data);
        return response()->json(["message" => "SpareType Updated Successfully"]); 
    }

    public function deleteSpareType(Request $request)
    {
        $request->validate([
            'spare_type_id' => 'required|exists:spare_types,spare_type_id'
        ]);
        $spare_type = SpareType::withTrashed()->where('spare_type_id',$request->spare_type_id)->first();

        if($spare_type->trashed())
        {
            $spare_type->restore();
            return response()->json([
                "message" =>"SpareType Activated successfully"
            ],200);
        }
        else
        {
            $spare_type->delete();
            return response()->json([
                "message" =>"SpareType Deactivated successfully"
            ], 200);
        }
    }
}
